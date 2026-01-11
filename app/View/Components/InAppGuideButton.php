<?php

namespace App\View\Components;

use App\Services\InAppGuideService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InAppGuideButton extends Component
{
    public ?array $guide;
    public bool $hasGuide;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->guide = InAppGuideService::getCurrentGuide();
        $this->hasGuide = $this->guide !== null;
    }

    /**
     * Determine if component should be rendered.
     */
    public function shouldRender(): bool
    {
        return $this->hasGuide;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.in-app-guide-button');
    }
}
