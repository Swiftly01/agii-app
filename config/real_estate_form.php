<?php

// Declarative definition of the Real Estate listing form, add a field here and the view, validation and storage pick it up.
return [
    'category_slug' => 'real-estate',
    'category_label' => 'Houses & Apartments for Rent',

    // Order of the visual blocks on the page
    'sections' => ['basic', 'details', 'pricing', 'contact'],

    'photos' => [
        'min' => 5,
        'max' => 10,
        'max_size_mb' => 10,
        'mimes' => ['jpg', 'jpeg', 'png', 'heic', 'webp'],
        // When true a listing with at least one video does not need the minimum photos
        'optional_with_video' => true,
    ],

    'videos' => [
        'max' => 2,
        'max_size_mb' => 100,
        'mimes' => ['mp4', 'mov', 'webm'],
        'mime_types' => ['video/mp4', 'video/quicktime', 'video/webm'],
    ],

    // Types: text, textarea, number, url, select, multiselect, checkbox, radio (select mode: page or sheet, column: products column, else specifications)
    'fields' => [
        'title' => [
            'type' => 'text', 'section' => 'basic', 'label' => 'Title', 'required' => true,
            'max' => 70, 'hint' => 'Please write a clear title for your item', 'column' => 'title',
        ],
        'video_url' => [
            'type' => 'url', 'section' => 'basic', 'label' => 'Link to Youtube or Facebook video',
            'max' => 1024, 'counter' => true,
        ],
        'locality' => [
            'type' => 'select', 'section' => 'basic', 'label' => 'Property locality', 'required' => true,
            'mode' => 'page', 'icon' => 'geo-alt', 'options_source' => 'localities', 'default_from' => 'local_government',
        ],
        'address' => [
            'type' => 'text', 'section' => 'basic', 'label' => 'Property Address', 'required' => true,
            'max' => 60, 'counter' => true, 'hint' => 'e.g 12, Gwani Street',
        ],
        'estate_name' => [
            'type' => 'text', 'section' => 'basic', 'label' => 'Estate Name',
            'max' => 60, 'counter' => true, 'hint' => 'e.g Emerald Estate',
        ],
        'property_size' => [
            'type' => 'text', 'section' => 'details', 'label' => 'Property Size', 'max' => 30,
        ],
        'property_type' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Property Type', 'required' => true, 'mode' => 'page',
            'options' => [
                'Apartment', 'Bedsitter', 'Block of Flats', 'Bungalow', 'Chalet', 'Condo', 'Duplex', 'Farm House',
                'House', 'Maisonette', 'Mansion', 'Mini Flat', 'Penthouse', 'Room & Parlour', 'Self-Contain',
                'Shared Apartment', 'Terraced House', 'Townhouse',
            ],
        ],
        'condition' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Condition', 'required' => true, 'mode' => 'sheet',
            'column' => 'condition', 'options' => ['Fairly Used', 'Newly-Built', 'Old', 'Renovated'],
        ],
        'furnishing' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Furnishing', 'required' => true, 'mode' => 'sheet',
            'options' => ['Furnished', 'Semi-Furnished', 'Unfurnished'],
        ],
        'bedrooms' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Bedrooms', 'required' => true, 'mode' => 'page',
            'options' => range(1, 20), 'popular' => [2, 3, 1, 4, 5],
        ],
        'bathrooms' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Bathrooms', 'required' => true, 'mode' => 'page',
            'options' => range(1, 20), 'popular' => [3, 4, 2, 1, 5],
        ],
        'toilets' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Toilets', 'mode' => 'page',
            'options' => range(1, 10), 'any' => true,
        ],
        'facilities' => [
            'type' => 'multiselect', 'section' => 'details', 'label' => 'Facilities', 'mode' => 'page',
            'options' => [
                'Appliances', 'Backup Generator', 'BBQ Area', 'CCTV', 'Electricity Backup', 'Elevator / Lift',
                'Emergency Exits', 'En Suite Bathroom', 'Firefighting Equipment', 'Fitted Kitchen', 'Gym', 'Intercom',
                'Parking Space', 'Postpaid Meter', 'Prepaid Meter', 'Sectionals', 'Security', 'Swimming Pool',
                'Water Supply',
            ],
        ],
        'listing_by' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Listing by', 'mode' => 'sheet',
            'options' => ['Agent', 'Owner'],
        ],
        'service_charge' => [
            'type' => 'checkbox', 'section' => 'details', 'label' => 'Service Charge',
        ],
        'minimum_rental_period' => [
            'type' => 'select', 'section' => 'details', 'label' => 'Minimum Rental Period', 'required' => true, 'mode' => 'sheet',
            'options' => ['1 month', '3 months', '6 months', '1 year', '2 years', '3 years or more'],
        ],
        'description' => [
            'type' => 'textarea', 'section' => 'details', 'label' => 'Description', 'required' => true, 'max' => 850,
            'counter' => true, 'column' => 'description',
            'hint' => 'Please provide a detailed description. Mentioning as many details as possible will make your Ad more attractive to buyers',
        ],
        'price' => [
            'type' => 'number', 'section' => 'pricing', 'label' => 'Price', 'required' => true, 'max' => 99999999, 'column' => 'price',
        ],
        'price_period' => [
            'type' => 'select', 'section' => 'pricing', 'label' => 'Price period', 'required' => true, 'mode' => 'sheet',
            'options' => ['per annum', 'per month', 'per quarter', 'per day'], 'default' => 'per annum',
        ],
        'negotiation' => [
            'type' => 'radio', 'section' => 'pricing', 'label' => 'Are you open to negotiation?', 'required' => true,
            'options' => ['Yes', 'No', 'Not sure'], 'default' => 'Not sure',
        ],
    ],
];
