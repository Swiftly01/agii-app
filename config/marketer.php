<?php

return [

    // Share of every activated vendor subscription that is credited to the marketer who referred the vendor
    'commission_rate' => (float) env('MARKETER_COMMISSION_RATE', 20),

    // Keep false while vendor subscriptions are not live, set true later to make marketers wait for the vendor to subscribe
    'require_vendor_subscription_to_post' => (bool) env('MARKETER_REQUIRE_SUBSCRIPTION_TO_POST', false),

];
