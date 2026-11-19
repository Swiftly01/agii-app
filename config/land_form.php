<?php

// Land & Plots for Sale, reuses the shared rent form fields and adds the land-specific ones.
$rent = require __DIR__ . '/real_estate_form.php';

// A rent form field with optional overrides, so shared fields are defined once
$shared = fn (string $name, array $override = []): array => [...$rent['fields'][$name], ...$override];

return [
    ...$rent,
    'category_slug' => 'land-plots-for-sale',
    'category_label' => 'Land & Plots for Sale',

    // Land listings need fewer photos than houses
    'photos' => [...$rent['photos'], 'min' => 2],

    'fields' => [
        'title' => $shared('title'),
        'video_url' => $shared('video_url'),
        'locality' => $shared('locality'),
        'land_type' => [
            'type' => 'select', 'section' => 'basic', 'label' => 'Type', 'required' => true, 'mode' => 'page',
            'options' => ['Commercial Land', 'Farmland', 'Industrial Land', 'Mixed-Use Land', 'Quarry', 'Residential Land'],
        ],
        'address' => $shared('address', ['label' => 'Address', 'max' => 50, 'hint' => null]),
        'square_metres' => [
            'type' => 'number', 'section' => 'basic', 'label' => 'Square Metres', 'required' => true, 'max' => 999999999,
        ],
        'property_use' => [
            'type' => 'select', 'section' => 'basic', 'label' => 'Property Use', 'required' => true, 'mode' => 'sheet',
            'options' => ['Commercial', 'Mixed', 'Residential'],
        ],
        'facilities' => [
            'type' => 'multiselect', 'section' => 'details', 'label' => 'Facilities', 'mode' => 'page',
            'options' => ['Car Parking', 'Domestic Sewage', 'Electric Supply', 'Gas Supply', 'Rain Water Drainage', 'Water Supply'],
        ],
        'development_status' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Development Status', 'mode' => 'sheet',
            'options' => ['Undeveloped', 'Cleared', 'Fenced', 'Partially Developed', 'Fully Developed'],
        ],
        'access_road' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Access Road', 'mode' => 'sheet',
            'options' => ['Paved Road', 'Gravel Road', 'Dirt Road', 'Unpaved Road', 'No Direct Road Access'],
        ],
        // Named title_document because "title" is already the ad title
        'title_document' => [
            'type' => 'multiselect', 'section' => 'details', 'label' => 'Title', 'mode' => 'page',
            'options' => [
                'Certificate of Occupancy', 'Customary Ownership', 'Deed of Assignment', 'Deed of Conveyance', 'Gazette',
                "Governor's Consent", 'Power of Attorney', 'Right of Occupancy', 'Sale Agreement',
            ],
        ],
        'service_charge' => $rent['fields']['service_charge'],
        'listing_by' => $shared('listing_by'),
        'description' => $shared('description'),
        // Land prices run far higher than rent
        'price' => $shared('price', ['max' => 99999999999]),
        'price_type' => [
            'type' => 'select', 'section' => 'pricing', 'label' => 'Price type', 'mode' => 'sheet',
            'options' => ['Outright Price', 'Instalment'], 'default' => 'Outright Price',
        ],
        'negotiation' => $shared('negotiation'),
    ],
];
