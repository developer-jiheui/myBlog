<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    /** Public props available in the Blade view */
    public string $id;
    public ?string $title;
    public ?string $message;
    public string $variant; // info|warning|success|error etc.
    public string $size;    // md|lg (optional)

    public function __construct(
        string  $id = 'appModal',
        ?string $title = null,
        ?string $message = null,
        string  $variant = 'info',
        string  $size = 'md'
    )
    {
        $this->id = $id;
        $this->title = $title ?? $this->defaultTitleForRoute();
        $this->message = $message ?? $this->defaultMessageForRoute();
        $this->variant = $variant;
        $this->size = $size;
    }

    /** Choose a sensible default title per route (optional) */
    protected function defaultTitleForRoute(): string
    {
        $route = request()->route()->getName();

        return match (true) {
            ($route === 'page.show' && request()->route('name') === 'home') || $route === 'home'
            => '🚧 Webpage building in progress 🚧',
            $route === 'page.show' && request()->route('name') === 'about'
            => 'About this site',
            $route === 'page.show' && request()->route('name') === 'portfolio'
            => 'Portfolio Notice',
            default => 'Notice',
        };
    }

    /** Auto message based on route (you can expand this map anytime) */
    protected function defaultMessageForRoute(): string
    {
        $route = request()->route()->getName();
        $page = request()->route('name');

        return match (true) {
            ($route === 'page.show' && request()->route('name') === 'home') || $route === 'home'
            => "This site is under construction. You can use Guest Login to explore without signing up.",
            $route === 'page.show' && $page === 'about'
            => "This page is being updated. Thanks for your patience!",
            $route === 'page.show' && $page === 'portfolio'
            => "New projects are rolling out soon. Filter and details will appear here.",
            default
            => "Content is being updated. Please check back soon.",
        };
    }

    protected function homeDefault()
    {

    }

    public function render()
    {
        // Renders resources/views/components/modal.blade.php
        return view('components.modal');
    }
}
