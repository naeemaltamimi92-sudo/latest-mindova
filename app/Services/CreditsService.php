<?php

namespace App\Services;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreditsService
{
    /**
     * Award earned credits (e.g., idea accepted, submission approved).
     */
    public function award(User $user, int $amount, string $description, ?Model $related = null): void
    {
        $this->record($user, abs($amount), 'earned', $description, $related);
    }

    /**
     * Record a credit purchase (called by payment gateway callback).
     */
    public function purchase(User $user, int $amount, string $description = 'Credit purchase'): void
    {
        $this->record($user, abs($amount), 'purchase', $description, null);
    }

    /**
     * Gift credits to a user (admin action).
     */
    public function gift(User $user, int $amount, string $description): void
    {
        $this->record($user, abs($amount), 'gifted', $description, null);
    }

    /**
     * Award credits from the platform (prizes, bonuses).
     */
    public function grantAward(User $user, int $amount, string $description, ?Model $related = null): void
    {
        $this->record($user, abs($amount), 'awarded', $description, $related);
    }

    /**
     * Spend credits. Returns false if the user cannot afford it.
     * Lock-then-check inside the transaction prevents double-spend race conditions.
     */
    public function spend(User $user, int $amount, string $description, ?Model $related = null): bool
    {
        $amount  = abs($amount);
        $success = false;

        DB::transaction(function () use ($user, $amount, $description, $related, &$success) {
            $locked = User::lockForUpdate()->find($user->id);

            if ($locked->credits_balance < $amount) {
                return;
            }

            $locked->decrement('credits_balance', $amount);
            $newBalance = (int) $locked->fresh()->credits_balance;

            CreditTransaction::create([
                'user_id'      => $locked->id,
                'amount'       => -$amount,
                'balance_after' => $newBalance,
                'type'         => 'spent',
                'description'  => $description,
                'related_type' => $related ? get_class($related) : null,
                'related_id'   => $related?->id,
                'created_at'   => now(),
            ]);

            $user->credits_balance = $newBalance;
            $success = true;
        });

        return $success;
    }

    /**
     * Refund credits back to a user.
     */
    public function refund(User $user, int $amount, string $description, ?Model $related = null): void
    {
        $this->record($user, abs($amount), 'refunded', $description, $related);
    }

    public function getBalance(User $user): int
    {
        return (int) $user->credits_balance;
    }

    private function record(User $user, int $amount, string $type, string $description, ?Model $related): void
    {
        DB::transaction(function () use ($user, $amount, $type, $description, $related) {
            $user->increment('credits_balance', $amount);

            // Clamp to zero in the unlikely case of a race condition on a debit
            if ($user->credits_balance < 0) {
                $user->update(['credits_balance' => 0]);
            }

            CreditTransaction::create([
                'user_id'      => $user->id,
                'amount'       => $amount,
                'balance_after' => (int) $user->credits_balance,
                'type'         => $type,
                'description'  => $description,
                'related_type' => $related ? get_class($related) : null,
                'related_id'   => $related?->id,
                'created_at'   => now(),
            ]);
        });
    }
}
