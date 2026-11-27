<?php

return [
    // Price in naira a vendor pays for one month in the homepage hero
    'monthly_price' => (int) env('HERO_SPOTLIGHT_MONTHLY_PRICE', 2000),

    // Most slides the hero carousel shows at once, vendors rotate fairly when more are paid up
    'max_slides' => (int) env('HERO_SPOTLIGHT_MAX_SLIDES', 6),

    // Switch to false to stop new purchases, running spotlights stay live until they expire
    'enabled' => (bool) env('HERO_SPOTLIGHT_ENABLED', true),
];
