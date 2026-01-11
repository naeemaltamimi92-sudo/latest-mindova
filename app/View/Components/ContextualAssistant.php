<?php

namespace App\View\Components;

use App\Services\ContextualAssistantService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContextualAssistant extends Component
{
    public ?array $guidance;
    public bool $isEnabled;
    public bool $isDismissed;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->isEnabled = ContextualAssistantService::isEnabled();
        $this->isDismissed = ContextualAssistantService::isDismissed();
        $this->guidance = $this->isEnabled ? ContextualAssistantService::getCurrentGuidance() : null;
    }

    /**
     * Determine if component should be rendered.
     */
    public function shouldRender(): bool
    {
        return $this->isEnabled && !$this->isDismissed && $this->guidance !== null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contextual-assistant');
    }
}
