<?php

namespace App\Enums;

// Add a case here to start tracking a new kind of event
enum AnalyticsEventType: string
{
    case Login = 'login';
    case ProductClick = 'product_click';
}
