<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{  
    /*
    public function run()
    {
        $categories = [
            // Product Categories
            [
                'name' => 'Electronics & Appliances',
                'slug' => 'electronics-appliances',
                'type' => 'product',
                'description' => 'Home electronics, kitchen appliances, and household gadgets',
                'icon' => 'tv',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'power_consumption' => [
                        'type' => 'text',
                        'label' => 'Power Consumption',
                        'placeholder' => 'e.g., 1000W, 220V'
                    ],
                    'warranty' => [
                        'type' => 'select',
                        'label' => 'Warranty',
                        'options' => ['No Warranty', '1 Month', '3 Months', '6 Months', '1 Year', '2 Years']
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair', 'For Parts']
                    ]
                ]
            ],
            [
                'name' => 'Mobile Phones & Tablets',
                'slug' => 'mobile-phones-tablets',
                'type' => 'product',
                'description' => 'Smartphones, feature phones, tablets and mobile accessories',
                'icon' => 'phone',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'storage' => [
                        'type' => 'select',
                        'label' => 'Storage',
                        'options' => ['16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB']
                    ],
                    'ram' => [
                        'type' => 'select',
                        'label' => 'RAM',
                        'options' => ['2GB', '4GB', '6GB', '8GB', '12GB', '16GB']
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ],
                    'battery_health' => [
                        'type' => 'text',
                        'label' => 'Battery Health',
                        'placeholder' => 'e.g., 95%, 80%'
                    ],
                    'network_type' => [
                        'type' => 'select',
                        'label' => 'Network',
                        'options' => ['2G', '3G', '4G', '5G', 'Unlocked']
                    ]
                ]
            ],
            [
                'name' => 'Computers & Laptops',
                'slug' => 'computers-laptops',
                'type' => 'product',
                'description' => 'Laptops, desktops, computer accessories and peripherals',
                'icon' => 'laptop',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'processor' => [
                        'type' => 'text',
                        'label' => 'Processor',
                        'placeholder' => 'e.g., Intel i5, AMD Ryzen 5'
                    ],
                    'ram' => [
                        'type' => 'select',
                        'label' => 'RAM',
                        'options' => ['4GB', '8GB', '16GB', '32GB', '64GB']
                    ],
                    'storage' => [
                        'type' => 'select',
                        'label' => 'Storage',
                        'options' => ['256GB SSD', '512GB SSD', '1TB HDD', '1TB SSD', '2TB HDD']
                    ],
                    'graphics' => [
                        'type' => 'text',
                        'label' => 'Graphics Card'
                    ],
                    'screen_size' => [
                        'type' => 'text',
                        'label' => 'Screen Size',
                        'placeholder' => 'e.g., 14", 15.6", 17"'
                    ]
                ]
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'type' => 'product',
                'description' => 'Furniture, kitchenware, home decor and household items',
                'icon' => 'house',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand'
                    ],
                    'material' => [
                        'type' => 'text',
                        'label' => 'Material',
                        'placeholder' => 'e.g., Wood, Plastic, Steel'
                    ],
                    'dimensions' => [
                        'type' => 'text',
                        'label' => 'Dimensions',
                        'placeholder' => 'e.g., 50x30x40 cm'
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair']
                    ]
                ]
            ],
            [
                'name' => 'Fashion & Clothing',
                'slug' => 'fashion-clothing',
                'type' => 'product',
                'description' => 'Clothes, shoes, bags, accessories and fashion items',
                'icon' => 'bag',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand'
                    ],
                    'size' => [
                        'type' => 'text',
                        'label' => 'Size',
                        'required' => true
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color',
                        'required' => true
                    ],
                    'material' => [
                        'type' => 'text',
                        'label' => 'Material'
                    ],
                    'gender' => [
                        'type' => 'select',
                        'label' => 'Gender',
                        'options' => ['Men', 'Women', 'Unisex', 'Children']
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair']
                    ]
                ]
            ],
            [
                'name' => 'Vehicles & Automotive',
                'slug' => 'vehicles-automotive',
                'type' => 'product',
                'description' => 'Cars, motorcycles, auto parts and accessories',
                'icon' => 'car',
                'specifications_template' => [
                    'make' => [
                        'type' => 'text',
                        'label' => 'Make',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'year' => [
                        'type' => 'number',
                        'label' => 'Year',
                        'required' => true
                    ],
                    'mileage' => [
                        'type' => 'number',
                        'label' => 'Mileage (km)'
                    ],
                    'fuel_type' => [
                        'type' => 'select',
                        'label' => 'Fuel Type',
                        'options' => ['Petrol', 'Diesel', 'Electric', 'Hybrid']
                    ],
                    'transmission' => [
                        'type' => 'select',
                        'label' => 'Transmission',
                        'options' => ['Manual', 'Automatic']
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ]
                ]
            ],
            [
                'name' => 'Real Estate',
                'slug' => 'real-estate',
                'type' => 'product',
                'description' => 'Houses, apartments, lands and commercial properties',
                'icon' => 'building',
                'specifications_template' => [
                    'property_type' => [
                        'type' => 'select',
                        'label' => 'Property Type',
                        'required' => true,
                        'options' => ['Apartment', 'House', 'Land', 'Commercial', 'Office Space']
                    ],
                    'bedrooms' => [
                        'type' => 'number',
                        'label' => 'Bedrooms'
                    ],
                    'bathrooms' => [
                        'type' => 'number',
                        'label' => 'Bathrooms'
                    ],
                    'area' => [
                        'type' => 'number',
                        'label' => 'Area (sqm)',
                        'required' => true
                    ],
                    'furnished' => [
                        'type' => 'select',
                        'label' => 'Furnished',
                        'options' => ['Fully Furnished', 'Semi-Furnished', 'Unfurnished']
                    ],
                    'parking' => [
                        'type' => 'select',
                        'label' => 'Parking',
                        'options' => ['Available', 'Not Available']
                    ]
                ]
            ],

            // Service Categories
            [
                'name' => 'Repairs & Maintenance',
                'slug' => 'repairs-maintenance',
                'type' => 'service',
                'description' => 'Electronic repairs, home maintenance, and fixing services',
                'icon' => 'tools',
                'specifications_template' => [
                    'service_type' => [
                        'type' => 'text',
                        'label' => 'Service Type',
                        'required' => true
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'tools_available' => [
                        'type' => 'text',
                        'label' => 'Tools Available'
                    ],
                    'warranty_offered' => [
                        'type' => 'select',
                        'label' => 'Warranty Offered',
                        'options' => ['No Warranty', '30 Days', '90 Days', '1 Year']
                    ]
                ]
            ],
            [
                'name' => 'Cleaning Services',
                'slug' => 'cleaning-services',
                'type' => 'service',
                'description' => 'Home cleaning, office cleaning, and sanitation services',
                'icon' => 'broom',
                'specifications_template' => [
                    'service_type' => [
                        'type' => 'text',
                        'label' => 'Service Type',
                        'required' => true
                    ],
                    'equipment' => [
                        'type' => 'text',
                        'label' => 'Equipment Used'
                    ],
                    'team_size' => [
                        'type' => 'select',
                        'label' => 'Team Size',
                        'options' => ['Individual', '2-3 People', '4+ People']
                    ],
                    'eco_friendly' => [
                        'type' => 'select',
                        'label' => 'Eco-friendly',
                        'options' => ['Yes', 'No']
                    ]
                ]
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'type' => 'service',
                'description' => 'Hair styling, makeup, nail services and beauty treatments',
                'icon' => 'scissors',
                'specifications_template' => [
                    'specialization' => [
                        'type' => 'text',
                        'label' => 'Specialization',
                        'required' => true
                    ],
                    'certification' => [
                        'type' => 'text',
                        'label' => 'Certification'
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'products_used' => [
                        'type' => 'text',
                        'label' => 'Products Used'
                    ]
                ]
            ],
            [
                'name' => 'Tutoring & Education',
                'slug' => 'tutoring-education',
                'type' => 'service',
                'description' => 'Academic tutoring, music lessons, and educational services',
                'icon' => 'book',
                'specifications_template' => [
                    'subjects' => [
                        'type' => 'text',
                        'label' => 'Subjects',
                        'required' => true
                    ],
                    'qualification' => [
                        'type' => 'text',
                        'label' => 'Qualification',
                        'required' => true
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'teaching_method' => [
                        'type' => 'select',
                        'label' => 'Teaching Method',
                        'options' => ['Online', 'In-person', 'Both']
                    ]
                ]
            ],
            [
                'name' => 'Delivery Services',
                'slug' => 'delivery-services',
                'type' => 'service',
                'description' => 'Package delivery, food delivery, and courier services',
                'icon' => 'truck',
                'specifications_template' => [
                    'service_area' => [
                        'type' => 'text',
                        'label' => 'Service Area',
                        'required' => true
                    ],
                    'vehicle_type' => [
                        'type' => 'select',
                        'label' => 'Vehicle Type',
                        'options' => ['Motorcycle', 'Car', 'Truck', 'Bicycle']
                    ],
                    'delivery_time' => [
                        'type' => 'text',
                        'label' => 'Delivery Time'
                    ],
                    'max_weight' => [
                        'type' => 'text',
                        'label' => 'Max Weight Capacity'
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Now create subcategories for Electronics & Appliances
        $electronics = Category::where('slug', 'electronics-appliances')->first();

        if ($electronics) {
            $electronicsSubcategories = [
                [
                    'name' => 'Home Appliances',
                    'slug' => 'home-appliances',
                    'type' => 'product',
                    'description' => 'Refrigerators, washing machines, air conditioners, and home appliances',
                    'icon' => 'fan',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'capacity' => [
                            'type' => 'text',
                            'label' => 'Capacity',
                            'placeholder' => 'e.g., 10kg, 200L, 1.5HP'
                        ],
                        'energy_rating' => [
                            'type' => 'select',
                            'label' => 'Energy Rating',
                            'options' => ['A+++', 'A++', 'A+', 'A', 'B', 'C', 'D']
                        ],
                        'color' => [
                            'type' => 'text',
                            'label' => 'Color'
                        ],
                        'warranty' => [
                            'type' => 'select',
                            'label' => 'Warranty',
                            'options' => ['No Warranty', '1 Month', '3 Months', '6 Months', '1 Year', '2 Years']
                        ]
                    ]
                ],
                [
                    'name' => 'Kitchen Appliances',
                    'slug' => 'kitchen-appliances',
                    'type' => 'product',
                    'description' => 'Blenders, microwaves, cookers, and kitchen gadgets',
                    'icon' => 'egg-fried',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'power' => [
                            'type' => 'text',
                            'label' => 'Power',
                            'placeholder' => 'e.g., 500W, 1000W'
                        ],
                        'capacity' => [
                            'type' => 'text',
                            'label' => 'Capacity',
                            'placeholder' => 'e.g., 1.5L, 20L'
                        ],
                        'color' => [
                            'type' => 'text',
                            'label' => 'Color'
                        ],
                        'features' => [
                            'type' => 'textarea',
                            'label' => 'Special Features',
                            'placeholder' => 'e.g., Digital display, Multiple speed settings'
                        ]
                    ]
                ],
                [
                    'name' => 'Fans & Cooling',
                    'slug' => 'fans-cooling',
                    'type' => 'product',
                    'description' => 'Ceiling fans, standing fans, table fans, and cooling appliances',
                    'icon' => 'wind',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'fan_type' => [
                            'type' => 'select',
                            'label' => 'Fan Type',
                            'options' => ['Ceiling Fan', 'Standing Fan', 'Table Fan', 'Wall Fan', 'Exhaust Fan']
                        ],
                        'blade_size' => [
                            'type' => 'text',
                            'label' => 'Blade Size',
                            'placeholder' => 'e.g., 48", 56"'
                        ],
                        'speed_settings' => [
                            'type' => 'select',
                            'label' => 'Speed Settings',
                            'options' => ['1 Speed', '3 Speeds', '5 Speeds', 'Variable Speed']
                        ],
                        'remote_control' => [
                            'type' => 'select',
                            'label' => 'Remote Control',
                            'options' => ['Yes', 'No']
                        ]
                    ]
                ],
                [
                    'name' => 'Irons & Garment Care',
                    'slug' => 'irons-garment-care',
                    'type' => 'product',
                    'description' => 'Electric irons, steam irons, garment steamers, and clothing care',
                    'icon' => 'iron',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'iron_type' => [
                            'type' => 'select',
                            'label' => 'Iron Type',
                            'options' => ['Dry Iron', 'Steam Iron', 'Garment Steamer', 'Travel Iron']
                        ],
                        'power' => [
                            'type' => 'text',
                            'label' => 'Power',
                            'placeholder' => 'e.g., 1000W, 1500W, 2000W'
                        ],
                        'water_tank_capacity' => [
                            'type' => 'text',
                            'label' => 'Water Tank Capacity',
                            'placeholder' => 'e.g., 200ml, 300ml'
                        ],
                        'auto_shutoff' => [
                            'type' => 'select',
                            'label' => 'Auto Shut-off',
                            'options' => ['Yes', 'No']
                        ]
                    ]
                ],
                [
                    'name' => 'Televisions & Audio',
                    'slug' => 'televisions-audio',
                    'type' => 'product',
                    'description' => 'Smart TVs, home theater systems, speakers, and audio equipment',
                    'icon' => 'tv',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'screen_size' => [
                            'type' => 'text',
                            'label' => 'Screen Size',
                            'placeholder' => 'e.g., 32", 43", 55", 65"'
                        ],
                        'resolution' => [
                            'type' => 'select',
                            'label' => 'Resolution',
                            'options' => ['HD', 'Full HD', '4K Ultra HD', '8K Ultra HD']
                        ],
                        'smart_tv' => [
                            'type' => 'select',
                            'label' => 'Smart TV',
                            'options' => ['Yes', 'No']
                        ],
                        'connectivity' => [
                            'type' => 'text',
                            'label' => 'Connectivity',
                            'placeholder' => 'e.g., HDMI, USB, WiFi'
                        ]
                    ]
                ],
                [
                    'name' => 'Other Electronics',
                    'slug' => 'other-electronics',
                    'type' => 'product',
                    'description' => 'Other electronic devices and gadgets',
                    'icon' => 'cpu',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'device_type' => [
                            'type' => 'text',
                            'label' => 'Device Type',
                            'required' => true
                        ],
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand'
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model'
                        ],
                        'specifications' => [
                            'type' => 'textarea',
                            'label' => 'Specifications',
                            'placeholder' => 'Describe the device features and specifications'
                        ]
                    ]
                ]
            ];

            foreach ($electronicsSubcategories as $subcategoryData) {
                Category::create($subcategoryData);
            }
        }

        // Create subcategories for Home Appliances
        $homeAppliances = Category::where('slug', 'home-appliances')->first();

        if ($homeAppliances) {
            $applianceSubcategories = [
                [
                    'name' => 'Refrigerators & Freezers',
                    'slug' => 'refrigerators-freezers',
                    'type' => 'product',
                    'description' => 'Refrigerators, freezers, and cooling appliances',
                    'icon' => 'snow',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Washing Machines',
                    'slug' => 'washing-machines',
                    'type' => 'product',
                    'description' => 'Washing machines, dryers, and laundry appliances',
                    'icon' => 'droplet',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Air Conditioners',
                    'slug' => 'air-conditioners',
                    'type' => 'product',
                    'description' => 'AC units, split systems, and cooling systems',
                    'icon' => 'snowflake',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Water Heaters & Geysers',
                    'slug' => 'water-heaters-geysers',
                    'type' => 'product',
                    'description' => 'Water heaters, geysers, and heating systems',
                    'icon' => 'thermometer-sun',
                    'parent_id' => $homeAppliances->id
                ]
            ];

            foreach ($applianceSubcategories as $subcategoryData) {
                Category::create($subcategoryData);
            }
        }

        // Create subcategories for Kitchen Appliances
        $kitchenAppliances = Category::where('slug', 'kitchen-appliances')->first();

        if ($kitchenAppliances) {
            $kitchenSubcategories = [
                [
                    'name' => 'Blenders & Mixers',
                    'slug' => 'blenders-mixers',
                    'type' => 'product',
                    'description' => 'Blenders, food processors, and mixers',
                    'icon' => 'egg',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Microwaves & Ovens',
                    'slug' => 'microwaves-ovens',
                    'type' => 'product',
                    'description' => 'Microwave ovens, toasters, and baking appliances',
                    'icon' => 'microwave',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Cookers & Rice Cookers',
                    'slug' => 'cookers-rice-cookers',
                    'type' => 'product',
                    'description' => 'Electric cookers, rice cookers, and slow cookers',
                    'icon' => 'pot',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Electric Kettles',
                    'slug' => 'electric-kettles',
                    'type' => 'product',
                    'description' => 'Electric kettles and water boilers',
                    'icon' => 'cup-straw',
                    'parent_id' => $kitchenAppliances->id
                ]
            ];

            foreach ($kitchenSubcategories as $subcategoryData) {
                Category::create($subcategoryData);
            }
        }

        $this->command->info('Categories seeded successfully!');
    }

    */

     public function run()
    {
        $categories = [
            // Product Categories
            [
                'name' => 'Electronics & Appliances',
                'slug' => 'electronics-appliances',
                'type' => 'product',
                'description' => 'Home electronics, kitchen appliances, and household gadgets',
                'icon' => 'tv',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'power_consumption' => [
                        'type' => 'text',
                        'label' => 'Power Consumption',
                        'placeholder' => 'e.g., 1000W, 220V'
                    ],
                    'warranty' => [
                        'type' => 'select',
                        'label' => 'Warranty',
                        'options' => ['No Warranty', '1 Month', '3 Months', '6 Months', '1 Year', '2 Years']
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair', 'For Parts']
                    ]
                ]
            ],
            [
                'name' => 'Mobile Phones & Tablets',
                'slug' => 'mobile-phones-tablets',
                'type' => 'product',
                'description' => 'Smartphones, feature phones, tablets and mobile accessories',
                'icon' => 'phone',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'storage' => [
                        'type' => 'select',
                        'label' => 'Storage',
                        'options' => ['16GB', '32GB', '64GB', '128GB', '256GB', '512GB', '1TB']
                    ],
                    'ram' => [
                        'type' => 'select',
                        'label' => 'RAM',
                        'options' => ['2GB', '4GB', '6GB', '8GB', '12GB', '16GB']
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ],
                    'battery_health' => [
                        'type' => 'text',
                        'label' => 'Battery Health',
                        'placeholder' => 'e.g., 95%, 80%'
                    ],
                    'network_type' => [
                        'type' => 'select',
                        'label' => 'Network',
                        'options' => ['2G', '3G', '4G', '5G', 'Unlocked']
                    ]
                ]
            ],
            [
                'name' => 'Computers & Laptops',
                'slug' => 'computers-laptops',
                'type' => 'product',
                'description' => 'Laptops, desktops, computer accessories and peripherals',
                'icon' => 'laptop',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'processor' => [
                        'type' => 'text',
                        'label' => 'Processor',
                        'placeholder' => 'e.g., Intel i5, AMD Ryzen 5'
                    ],
                    'ram' => [
                        'type' => 'select',
                        'label' => 'RAM',
                        'options' => ['4GB', '8GB', '16GB', '32GB', '64GB']
                    ],
                    'storage' => [
                        'type' => 'select',
                        'label' => 'Storage',
                        'options' => ['256GB SSD', '512GB SSD', '1TB HDD', '1TB SSD', '2TB HDD']
                    ],
                    'graphics' => [
                        'type' => 'text',
                        'label' => 'Graphics Card'
                    ],
                    'screen_size' => [
                        'type' => 'text',
                        'label' => 'Screen Size',
                        'placeholder' => 'e.g., 14", 15.6", 17"'
                    ]
                ]
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'type' => 'product',
                'description' => 'Furniture, kitchenware, home decor and household items',
                'icon' => 'house',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand'
                    ],
                    'material' => [
                        'type' => 'text',
                        'label' => 'Material',
                        'placeholder' => 'e.g., Wood, Plastic, Steel'
                    ],
                    'dimensions' => [
                        'type' => 'text',
                        'label' => 'Dimensions',
                        'placeholder' => 'e.g., 50x30x40 cm'
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair']
                    ]
                ]
            ],
            [
                'name' => 'Fashion & Clothing',
                'slug' => 'fashion-clothing',
                'type' => 'product',
                'description' => 'Clothes, shoes, bags, accessories and fashion items',
                'icon' => 'bag',
                'specifications_template' => [
                    'brand' => [
                        'type' => 'text',
                        'label' => 'Brand'
                    ],
                    'size' => [
                        'type' => 'text',
                        'label' => 'Size',
                        'required' => true
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color',
                        'required' => true
                    ],
                    'material' => [
                        'type' => 'text',
                        'label' => 'Material'
                    ],
                    'gender' => [
                        'type' => 'select',
                        'label' => 'Gender',
                        'options' => ['Men', 'Women', 'Unisex', 'Children']
                    ],
                    'condition' => [
                        'type' => 'select',
                        'label' => 'Condition',
                        'required' => true,
                        'options' => ['Brand New', 'Used - Like New', 'Used - Good', 'Used - Fair']
                    ]
                ]
            ],
            [
                'name' => 'Vehicles & Automotive',
                'slug' => 'vehicles-automotive',
                'type' => 'product',
                'description' => 'Cars, motorcycles, auto parts and accessories',
                'icon' => 'car',
                'specifications_template' => [
                    'make' => [
                        'type' => 'text',
                        'label' => 'Make',
                        'required' => true
                    ],
                    'model' => [
                        'type' => 'text',
                        'label' => 'Model',
                        'required' => true
                    ],
                    'year' => [
                        'type' => 'number',
                        'label' => 'Year',
                        'required' => true
                    ],
                    'mileage' => [
                        'type' => 'number',
                        'label' => 'Mileage (km)'
                    ],
                    'fuel_type' => [
                        'type' => 'select',
                        'label' => 'Fuel Type',
                        'options' => ['Petrol', 'Diesel', 'Electric', 'Hybrid']
                    ],
                    'transmission' => [
                        'type' => 'select',
                        'label' => 'Transmission',
                        'options' => ['Manual', 'Automatic']
                    ],
                    'color' => [
                        'type' => 'text',
                        'label' => 'Color'
                    ]
                ]
            ],
            [
                'name' => 'Real Estate',
                'slug' => 'real-estate',
                'type' => 'product',
                'description' => 'Houses, apartments, lands and commercial properties',
                'icon' => 'building',
                'specifications_template' => [
                    'property_type' => [
                        'type' => 'select',
                        'label' => 'Property Type',
                        'required' => true,
                        'options' => ['Apartment', 'House', 'Land', 'Commercial', 'Office Space']
                    ],
                    'bedrooms' => [
                        'type' => 'number',
                        'label' => 'Bedrooms'
                    ],
                    'bathrooms' => [
                        'type' => 'number',
                        'label' => 'Bathrooms'
                    ],
                    'area' => [
                        'type' => 'number',
                        'label' => 'Area (sqm)',
                        'required' => true
                    ],
                    'furnished' => [
                        'type' => 'select',
                        'label' => 'Furnished',
                        'options' => ['Fully Furnished', 'Semi-Furnished', 'Unfurnished']
                    ],
                    'parking' => [
                        'type' => 'select',
                        'label' => 'Parking',
                        'options' => ['Available', 'Not Available']
                    ]
                ]
            ],

            // Service Categories
            [
                'name' => 'Repairs & Maintenance',
                'slug' => 'repairs-maintenance',
                'type' => 'service',
                'description' => 'Electronic repairs, home maintenance, and fixing services',
                'icon' => 'tools',
                'specifications_template' => [
                    'service_type' => [
                        'type' => 'text',
                        'label' => 'Service Type',
                        'required' => true
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'tools_available' => [
                        'type' => 'text',
                        'label' => 'Tools Available'
                    ],
                    'warranty_offered' => [
                        'type' => 'select',
                        'label' => 'Warranty Offered',
                        'options' => ['No Warranty', '30 Days', '90 Days', '1 Year']
                    ]
                ]
            ],
            [
                'name' => 'Cleaning Services',
                'slug' => 'cleaning-services',
                'type' => 'service',
                'description' => 'Home cleaning, office cleaning, and sanitation services',
                'icon' => 'broom',
                'specifications_template' => [
                    'service_type' => [
                        'type' => 'text',
                        'label' => 'Service Type',
                        'required' => true
                    ],
                    'equipment' => [
                        'type' => 'text',
                        'label' => 'Equipment Used'
                    ],
                    'team_size' => [
                        'type' => 'select',
                        'label' => 'Team Size',
                        'options' => ['Individual', '2-3 People', '4+ People']
                    ],
                    'eco_friendly' => [
                        'type' => 'select',
                        'label' => 'Eco-friendly',
                        'options' => ['Yes', 'No']
                    ]
                ]
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'type' => 'service',
                'description' => 'Hair styling, makeup, nail services and beauty treatments',
                'icon' => 'scissors',
                'specifications_template' => [
                    'specialization' => [
                        'type' => 'text',
                        'label' => 'Specialization',
                        'required' => true
                    ],
                    'certification' => [
                        'type' => 'text',
                        'label' => 'Certification'
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'products_used' => [
                        'type' => 'text',
                        'label' => 'Products Used'
                    ]
                ]
            ],
            [
                'name' => 'Tutoring & Education',
                'slug' => 'tutoring-education',
                'type' => 'service',
                'description' => 'Academic tutoring, music lessons, and educational services',
                'icon' => 'book',
                'specifications_template' => [
                    'subjects' => [
                        'type' => 'text',
                        'label' => 'Subjects',
                        'required' => true
                    ],
                    'qualification' => [
                        'type' => 'text',
                        'label' => 'Qualification',
                        'required' => true
                    ],
                    'experience' => [
                        'type' => 'select',
                        'label' => 'Experience',
                        'options' => ['Beginner', '1-2 Years', '3-5 Years', '5+ Years']
                    ],
                    'teaching_method' => [
                        'type' => 'select',
                        'label' => 'Teaching Method',
                        'options' => ['Online', 'In-person', 'Both']
                    ]
                ]
            ],
            [
                'name' => 'Delivery Services',
                'slug' => 'delivery-services',
                'type' => 'service',
                'description' => 'Package delivery, food delivery, and courier services',
                'icon' => 'truck',
                'specifications_template' => [
                    'service_area' => [
                        'type' => 'text',
                        'label' => 'Service Area',
                        'required' => true
                    ],
                    'vehicle_type' => [
                        'type' => 'select',
                        'label' => 'Vehicle Type',
                        'options' => ['Motorcycle', 'Car', 'Truck', 'Bicycle']
                    ],
                    'delivery_time' => [
                        'type' => 'text',
                        'label' => 'Delivery Time'
                    ],
                    'max_weight' => [
                        'type' => 'text',
                        'label' => 'Max Weight Capacity'
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(['slug' => $categoryData['slug']], $categoryData);
        }

        // Now create subcategories for Electronics & Appliances
        $electronics = Category::where('slug', 'electronics-appliances')->first();

        if ($electronics) {
            $electronicsSubcategories = [
                [
                    'name' => 'Home Appliances',
                    'slug' => 'home-appliances',
                    'type' => 'product',
                    'description' => 'Refrigerators, washing machines, air conditioners, and home appliances',
                    'icon' => 'fan',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'capacity' => [
                            'type' => 'text',
                            'label' => 'Capacity',
                            'placeholder' => 'e.g., 10kg, 200L, 1.5HP'
                        ],
                        'energy_rating' => [
                            'type' => 'select',
                            'label' => 'Energy Rating',
                            'options' => ['A+++', 'A++', 'A+', 'A', 'B', 'C', 'D']
                        ],
                        'color' => [
                            'type' => 'text',
                            'label' => 'Color'
                        ],
                        'warranty' => [
                            'type' => 'select',
                            'label' => 'Warranty',
                            'options' => ['No Warranty', '1 Month', '3 Months', '6 Months', '1 Year', '2 Years']
                        ]
                    ]
                ],
                [
                    'name' => 'Kitchen Appliances',
                    'slug' => 'kitchen-appliances',
                    'type' => 'product',
                    'description' => 'Blenders, microwaves, cookers, and kitchen gadgets',
                    'icon' => 'egg-fried',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'power' => [
                            'type' => 'text',
                            'label' => 'Power',
                            'placeholder' => 'e.g., 500W, 1000W'
                        ],
                        'capacity' => [
                            'type' => 'text',
                            'label' => 'Capacity',
                            'placeholder' => 'e.g., 1.5L, 20L'
                        ],
                        'color' => [
                            'type' => 'text',
                            'label' => 'Color'
                        ],
                        'features' => [
                            'type' => 'textarea',
                            'label' => 'Special Features',
                            'placeholder' => 'e.g., Digital display, Multiple speed settings'
                        ]
                    ]
                ],
                [
                    'name' => 'Fans & Cooling',
                    'slug' => 'fans-cooling',
                    'type' => 'product',
                    'description' => 'Ceiling fans, standing fans, table fans, and cooling appliances',
                    'icon' => 'wind',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'fan_type' => [
                            'type' => 'select',
                            'label' => 'Fan Type',
                            'options' => ['Ceiling Fan', 'Standing Fan', 'Table Fan', 'Wall Fan', 'Exhaust Fan']
                        ],
                        'blade_size' => [
                            'type' => 'text',
                            'label' => 'Blade Size',
                            'placeholder' => 'e.g., 48", 56"'
                        ],
                        'speed_settings' => [
                            'type' => 'select',
                            'label' => 'Speed Settings',
                            'options' => ['1 Speed', '3 Speeds', '5 Speeds', 'Variable Speed']
                        ],
                        'remote_control' => [
                            'type' => 'select',
                            'label' => 'Remote Control',
                            'options' => ['Yes', 'No']
                        ]
                    ]
                ],
                [
                    'name' => 'Irons & Garment Care',
                    'slug' => 'irons-garment-care',
                    'type' => 'product',
                    'description' => 'Electric irons, steam irons, garment steamers, and clothing care',
                    'icon' => 'iron',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'iron_type' => [
                            'type' => 'select',
                            'label' => 'Iron Type',
                            'options' => ['Dry Iron', 'Steam Iron', 'Garment Steamer', 'Travel Iron']
                        ],
                        'power' => [
                            'type' => 'text',
                            'label' => 'Power',
                            'placeholder' => 'e.g., 1000W, 1500W, 2000W'
                        ],
                        'water_tank_capacity' => [
                            'type' => 'text',
                            'label' => 'Water Tank Capacity',
                            'placeholder' => 'e.g., 200ml, 300ml'
                        ],
                        'auto_shutoff' => [
                            'type' => 'select',
                            'label' => 'Auto Shut-off',
                            'options' => ['Yes', 'No']
                        ]
                    ]
                ],
                [
                    'name' => 'Televisions & Audio',
                    'slug' => 'televisions-audio',
                    'type' => 'product',
                    'description' => 'Smart TVs, home theater systems, speakers, and audio equipment',
                    'icon' => 'tv',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand',
                            'required' => true
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model',
                            'required' => true
                        ],
                        'screen_size' => [
                            'type' => 'text',
                            'label' => 'Screen Size',
                            'placeholder' => 'e.g., 32", 43", 55", 65"'
                        ],
                        'resolution' => [
                            'type' => 'select',
                            'label' => 'Resolution',
                            'options' => ['HD', 'Full HD', '4K Ultra HD', '8K Ultra HD']
                        ],
                        'smart_tv' => [
                            'type' => 'select',
                            'label' => 'Smart TV',
                            'options' => ['Yes', 'No']
                        ],
                        'connectivity' => [
                            'type' => 'text',
                            'label' => 'Connectivity',
                            'placeholder' => 'e.g., HDMI, USB, WiFi'
                        ]
                    ]
                ],
                [
                    'name' => 'Other Electronics',
                    'slug' => 'other-electronics',
                    'type' => 'product',
                    'description' => 'Other electronic devices and gadgets',
                    'icon' => 'cpu',
                    'parent_id' => $electronics->id,
                    'specifications_template' => [
                        'device_type' => [
                            'type' => 'text',
                            'label' => 'Device Type',
                            'required' => true
                        ],
                        'brand' => [
                            'type' => 'text',
                            'label' => 'Brand'
                        ],
                        'model' => [
                            'type' => 'text',
                            'label' => 'Model'
                        ],
                        'specifications' => [
                            'type' => 'textarea',
                            'label' => 'Specifications',
                            'placeholder' => 'Describe the device features and specifications'
                        ]
                    ]
                ]
            ];

            foreach ($electronicsSubcategories as $subcategoryData) {
                Category::firstOrCreate(['slug' => $subcategoryData['slug']], $subcategoryData);
            }
        }

        // Create subcategories for Home Appliances
        $homeAppliances = Category::where('slug', 'home-appliances')->first();

        if ($homeAppliances) {
            $applianceSubcategories = [
                [
                    'name' => 'Refrigerators & Freezers',
                    'slug' => 'refrigerators-freezers',
                    'type' => 'product',
                    'description' => 'Refrigerators, freezers, and cooling appliances',
                    'icon' => 'snow',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Washing Machines',
                    'slug' => 'washing-machines',
                    'type' => 'product',
                    'description' => 'Washing machines, dryers, and laundry appliances',
                    'icon' => 'droplet',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Air Conditioners',
                    'slug' => 'air-conditioners',
                    'type' => 'product',
                    'description' => 'AC units, split systems, and cooling systems',
                    'icon' => 'snowflake',
                    'parent_id' => $homeAppliances->id
                ],
                [
                    'name' => 'Water Heaters & Geysers',
                    'slug' => 'water-heaters-geysers',
                    'type' => 'product',
                    'description' => 'Water heaters, geysers, and heating systems',
                    'icon' => 'thermometer-sun',
                    'parent_id' => $homeAppliances->id
                ]
            ];

            foreach ($applianceSubcategories as $subcategoryData) {
                Category::firstOrCreate(['slug' => $subcategoryData['slug']], $subcategoryData);
            }
        }

        // Create subcategories for Kitchen Appliances
        $kitchenAppliances = Category::where('slug', 'kitchen-appliances')->first();

        if ($kitchenAppliances) {
            $kitchenSubcategories = [
                [
                    'name' => 'Blenders & Mixers',
                    'slug' => 'blenders-mixers',
                    'type' => 'product',
                    'description' => 'Blenders, food processors, and mixers',
                    'icon' => 'egg',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Microwaves & Ovens',
                    'slug' => 'microwaves-ovens',
                    'type' => 'product',
                    'description' => 'Microwave ovens, toasters, and baking appliances',
                    'icon' => 'microwave',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Cookers & Rice Cookers',
                    'slug' => 'cookers-rice-cookers',
                    'type' => 'product',
                    'description' => 'Electric cookers, rice cookers, and slow cookers',
                    'icon' => 'pot',
                    'parent_id' => $kitchenAppliances->id
                ],
                [
                    'name' => 'Electric Kettles',
                    'slug' => 'electric-kettles',
                    'type' => 'product',
                    'description' => 'Electric kettles and water boilers',
                    'icon' => 'cup-straw',
                    'parent_id' => $kitchenAppliances->id
                ]
            ];

            foreach ($kitchenSubcategories as $subcategoryData) {
                Category::firstOrCreate(['slug' => $subcategoryData['slug']], $subcategoryData);
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}
