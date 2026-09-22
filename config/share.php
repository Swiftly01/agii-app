<?php

return [


    'providers' => [
        'whatsapp' => [
            'label' => 'WhatsApp',
            'icon' => 'fab fa-whatsapp',
            'color' => '#25D366',
            'url' => 'https://wa.me/?text={text}%20{url}',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'icon' => 'fab fa-facebook',
            'color' => '#1877F2',
            'url' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
        ],
        'twitter' => [
            'label' => 'Twitter / X',
            'icon' => 'fab fa-x-twitter',
            'color' => '#000000',
            'url' => 'https://twitter.com/intent/tweet?url={url}&text={text}',
        ],
        'telegram' => [
            'label' => 'Telegram',
            'icon' => 'fab fa-telegram',
            'color' => '#26A5E4',
            'url' => 'https://t.me/share/url?url={url}&text={text}',
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'icon' => 'fab fa-linkedin',
            'color' => '#0A66C2',
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url={url}',
        ],
        'email' => [
            'label' => 'Email',
            'icon' => 'fas fa-envelope',
            'color' => '#6c757d',
            'url' => 'mailto:?subject={text}&body={url}',
        ],

      
        'instagram' => [
            'label' => 'Instagram',
            'icon' => 'fab fa-instagram',
            'color' => '#E4405F',
            'url' => null,
        ],
    ],

];
