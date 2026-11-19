<?php

use Illuminate\Support\Arr;

// Houses & Apartments for Sale, built from the rent form with the rental-only fields swapped for sale ones.
$rent = require __DIR__ . '/real_estate_form.php';

$rentalOnly = ['minimum_rental_period', 'price_period', 'service_charge'];

$fields = [];
foreach (Arr::except($rent['fields'], $rentalOnly) as $name => $field) {
    // Ownership paperwork matters to buyers, so it sits right before the description
    if ($name === 'description') {
        $fields['title_document'] = [
            'type' => 'select', 'section' => 'details', 'label' => 'Title Document', 'mode' => 'sheet',
            'options' => [
                "Certificate of Occupancy", "Governor's Consent", 'Deed of Assignment', 'Registered Survey',
                'Gazette', 'Excision', 'Other',
            ],
        ];
    }

    $fields[$name] = $field;
}

// Sale prices run far higher than rent
$fields['price']['max'] = 99999999999;

return [
    ...$rent,
    'category_slug' => 'property-for-sale',
    'category_label' => 'Houses & Apartments for Sale',
    'fields' => $fields,
];