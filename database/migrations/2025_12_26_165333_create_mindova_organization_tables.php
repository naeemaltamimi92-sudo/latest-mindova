<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mindova Roles table
        Schema::create('mindova_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('level')->default(0);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // Mindova Permissions table
        Schema::create('mindova_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Role-Permission pivot table
        Schema::create('mindova_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('mindova_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('mindova_permissions')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        // Mindova Team Members table
        Schema::create('mindova_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('role_id')->constrained('mindova_roles')->onDelete('restrict');
            $table->string('email')->unique();
            $table->string('name');
            $table->string('temporary_password')->nullable();
            $table->boolean('password_changed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->foreignId('invited_by')->nullable()->constrained('mindova_team_members')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Mindova Audit Logs table
        Schema::create('mindova_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->nullable()->constrained('mindova_team_members')->onDelete('set null');
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
        });

        // Seed default roles
        $this->seedDefaultRoles();

        // Seed default permissions
        $this->seedDefaultPermissions();

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    private function seedDefaultRoles(): void
    {
        $roles = [
            ['name' => 'Owner', 'slug' => 'owner', 'description' => 'Full access to all platform features. Can manage all settings, users, roles, and permissions.', 'level' => 100, 'is_system' => true],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Platform management capabilities excluding ownership transfer.', 'level' => 80, 'is_system' => true],
            ['name' => 'Accounting', 'slug' => 'accounting', 'description' => 'Access to financial views and reports. No user management capabilities.', 'level' => 50, 'is_system' => true],
            ['name' => 'Support', 'slug' => 'support', 'description' => 'View and respond to user issues and reports. No system configuration access.', 'level' => 40, 'is_system' => true],
            ['name' => 'Feedback / QA', 'slug' => 'feedback-qa', 'description' => 'View bug reports and feedback. No administrative privileges.', 'level' => 30, 'is_system' => true],
        ];

        foreach ($roles as $role) {
            DB::table('mindova_roles')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function seedDefaultPermissions(): void
    {
        $permissions = [
            // Team Management
            ['name' => 'View Team Members', 'slug' => 'team.view', 'group' => 'team', 'description' => 'View list of team members'],
            ['name' => 'Create Team Members', 'slug' => 'team.create', 'group' => 'team', 'description' => 'Invite new team members'],
            ['name' => 'Edit Team Members', 'slug' => 'team.edit', 'group' => 'team', 'description' => 'Edit team member details'],
            ['name' => 'Delete Team Members', 'slug' => 'team.delete', 'group' => 'team', 'description' => 'Remove team members'],
            ['name' => 'Manage Roles', 'slug' => 'team.roles', 'group' => 'team', 'description' => 'Assign and change roles'],

            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'group' => 'users', 'description' => 'View platform users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'group' => 'users', 'description' => 'Edit user details'],
            ['name' => 'Ban Users', 'slug' => 'users.ban', 'group' => 'users', 'description' => 'Ban or suspend users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'users', 'description' => 'Delete user accounts'],

            // Company Management
            ['name' => 'View Companies', 'slug' => 'companies.view', 'group' => 'companies', 'description' => 'View registered companies'],
            ['name' => 'Edit Companies', 'slug' => 'companies.edit', 'group' => 'companies', 'description' => 'Edit company details'],
            ['name' => 'Verify Companies', 'slug' => 'companies.verify', 'group' => 'companies', 'description' => 'Verify company accounts'],
            ['name' => 'Delete Companies', 'slug' => 'companies.delete', 'group' => 'companies', 'description' => 'Delete company accounts'],

            // Opportunities
            ['name' => 'View Opportunities', 'slug' => 'opportunities.view', 'group' => 'opportunities', 'description' => 'View all opportunities'],
            ['name' => 'Edit Opportunities', 'slug' => 'opportunities.edit', 'group' => 'opportunities', 'description' => 'Edit opportunity details'],
            ['name' => 'Approve Opportunities', 'slug' => 'opportunities.approve', 'group' => 'opportunities', 'description' => 'Approve or reject opportunities'],
            ['name' => 'Delete Opportunities', 'slug' => 'opportunities.delete', 'group' => 'opportunities', 'description' => 'Delete opportunities'],

            // Financial
            ['name' => 'View Financial Reports', 'slug' => 'finance.view', 'group' => 'finance', 'description' => 'View financial reports and analytics'],
            ['name' => 'Export Financial Data', 'slug' => 'finance.export', 'group' => 'finance', 'description' => 'Export financial data'],
            ['name' => 'Manage Transactions', 'slug' => 'finance.transactions', 'group' => 'finance', 'description' => 'View and manage transactions'],

            // Support
            ['name' => 'View Support Tickets', 'slug' => 'support.view', 'group' => 'support', 'description' => 'View support tickets'],
            ['name' => 'Respond to Tickets', 'slug' => 'support.respond', 'group' => 'support', 'description' => 'Respond to support tickets'],
            ['name' => 'Close Tickets', 'slug' => 'support.close', 'group' => 'support', 'description' => 'Close support tickets'],
            ['name' => 'View Reports', 'slug' => 'support.reports', 'group' => 'support', 'description' => 'View user reports and complaints'],

            // Feedback & QA
            ['name' => 'View Bug Reports', 'slug' => 'feedback.bugs', 'group' => 'feedback', 'description' => 'View bug reports'],
            ['name' => 'View Feedback', 'slug' => 'feedback.view', 'group' => 'feedback', 'description' => 'View user feedback'],
            ['name' => 'Manage Bug Status', 'slug' => 'feedback.manage', 'group' => 'feedback', 'description' => 'Update bug report status'],

            // Platform Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'settings', 'description' => 'View platform settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'group' => 'settings', 'description' => 'Modify platform settings'],
            ['name' => 'Manage Integrations', 'slug' => 'settings.integrations', 'group' => 'settings', 'description' => 'Manage third-party integrations'],

            // Audit & Logs
            ['name' => 'View Audit Logs', 'slug' => 'audit.view', 'group' => 'audit', 'description' => 'View audit logs'],
            ['name' => 'Export Audit Logs', 'slug' => 'audit.export', 'group' => 'audit', 'description' => 'Export audit logs'],

            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'group' => 'dashboard', 'description' => 'View admin dashboard'],
            ['name' => 'View Analytics', 'slug' => 'dashboard.analytics', 'group' => 'dashboard', 'description' => 'View platform analytics'],
        ];

        foreach ($permissions as $permission) {
            DB::table('mindova_permissions')->insert(array_merge($permission, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    private function assignPermissionsToRoles(): void
    {
        $rolePermissions = [
            'owner' => ['*'],
            'admin' => [
                'team.view', 'users.*', 'companies.*', 'opportunities.*',
                'support.*', 'feedback.*', 'settings.view', 'settings.edit',
                'audit.view', 'dashboard.*',
            ],
            'accounting' => ['finance.*', 'dashboard.view', 'dashboard.analytics'],
            'support' => ['users.view', 'companies.view', 'opportunities.view', 'support.*', 'dashboard.view'],
            'feedback-qa' => ['feedback.*', 'dashboard.view'],
        ];

        $allPermissions = DB::table('mindova_permissions')->get();
        $roles = DB::table('mindova_roles')->get()->keyBy('slug');

        foreach ($rolePermissions as $roleSlug => $permissionPatterns) {
            $role = $roles[$roleSlug] ?? null;
            if (!$role) continue;

            foreach ($allPermissions as $permission) {
                $hasPermission = false;

                foreach ($permissionPatterns as $pattern) {
                    if ($pattern === '*') {
                        $hasPermission = true;
                        break;
                    }
                    if (str_ends_with($pattern, '.*')) {
                        $group = str_replace('.*', '', $pattern);
                        if (str_starts_with($permission->slug, $group . '.')) {
                            $hasPermission = true;
                            break;
                        }
                    } elseif ($permission->slug === $pattern) {
                        $hasPermission = true;
                        break;
                    }
                }

                if ($hasPermission) {
                    DB::table('mindova_role_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mindova_audit_logs');
        Schema::dropIfExists('mindova_team_members');
        Schema::dropIfExists('mindova_role_permissions');
        Schema::dropIfExists('mindova_permissions');
        Schema::dropIfExists('mindova_roles');
    }
};
