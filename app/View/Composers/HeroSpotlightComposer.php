<?php

namespace App\View\Composers;

use App\Services\Spotlight\SpotlightService;
use Illuminate\View\View;

// Feeds the hero carousel partial so no home page controller has to know about spotlights
final class HeroSpotlightComposer
{
    public function __construct(private readonly SpotlightService $spotlight) {}

    public function compose(View $view): void
    {
        $view->with('spotlightProducts', $this->spotlight->slides());
    }
}
