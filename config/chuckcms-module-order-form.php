<?php

return [

	'pages' => [
		'product_detail' => 'chuckcms-module-order-form::frontend.products.detail'

	],

	'products' => [
		'slug' 	=> 'order_form_products',
		'url' => 'of-products/',
		'page' => 'chuckcms-module-order-form::frontend.products.detail'
	],

	'discounts' => [
		'slug' 	=> 'order_form_discounts',
		'url' => 'of-discounts/',
		'page' => 'chuckcms-module-order-form::frontend.discounts.detail'
	],

	'categories' => [
		'slug' 	=> 'order_form_categories',
		'url' => 'of-categories/',
		'page' => 'chuckcms-module-order-form::frontend.categories.detail'
	],

	'locations' => [
		'slug' 	=> 'order_form_locations',
		'url' => 'of-locations/',
		'page' => 'chuckcms-module-order-form::frontend.locations.detail'
	],

	'rewards' => [
		'slug' 	=> 'order_form_rewards',
		'url' => 'of-rewards/',
		'page' => 'chuckcms-module-order-form::frontend.rewards.detail'
	],

	'customers' => [
		'table' 	=> 'cof_customers'
	],

	'coupons' => [
		'table' 	=> 'cof_coupons'
	],

	'auth' => [
		'template' => [
			'hintpath' => 'chuckcms-template-starter',
            'login_blade' => 'account.auth',
            'registration_blade' => 'account.register',
		],
	],

	'account' => [
		'template' => [
			'account' => 'order-form.account',
			'coupons' => 'order-form.coupons',
			'swap_points' => 'order-form.swap_points',
		],
	],

	// 'categories' => [
	// 	'category_1' 	=> [
	// 		'name' => 'Categorie 1',
	// 		'is_displayed' => true
	// 	],
	// 	'category_2' 	=> [
	// 		'name' => 'Categorie 2',
	// 		'is_displayed' => true
	// 	],
	// ],

	// 'form' => [
	// 	'display_images' 	=> true,
	// 	'display_description' => true,
	// 	'page' => 'chuckcms-module-order-form::frontend.products.detail'
	// ],

	// 'cart' => [
	// 	'use_ui' 	=> true
	// ],

	// 'order' => [
	// 	'has_minimum_order_price' 	=> true,
	// 	'minimum_order_price' => 15,
	// 	'legal_text' => 'Hierbij bevestig ik mijn bestelling en ga ik akkoord met de privacyvoorwaarden.',
	// 	'promo_check' => true,
	// 	'promo_text' => 'Hou mij op de hoogte van de laatste nieuwtjes, openingen en kortingen',
	// 	'payment_upfront' => false,
	// 	'payment_description' => 'Order #',
	// 	'redirect_url' => '/bedankt'
	// ],

	// 'emails' => [
	// 	'send_confirmation' => true,
	// 	'confirmation_subject' => 'Bevestiging van uw bestelling #',
	// 	'send_notication' => true,
	// 	'notification_subject' => 'Een nieuwe online bestelling #',
	// 	'from_email' => 'hello@chuck.be',
	// 	'from_name' => 'ChuckCMS Order',
	// 	'to_email' => 'hello@chuck.be',
	// 	'to_name' => 'ChuckCMS Order',
	// 	'to_cc' => false, // false or string with emails seperated by comma
	// 	'to_bcc' => false, // false or string with emails seperated by comma
	// ],

	// 'company' => [
	// 	'name' => 'ChuckCMS',
	// 	'vat' => 'BE0XXX.XXX.XXX',
	// 	'address1' => 'Berlaarsestraat 10',
	// 	'address2' => '2500 Lier',
	// 	'email' => 'hello@chuck.be'
	// ],

	// 'locations' => [
	// 	'afhalen' 	=> [
	// 		'type' => 'takeout',
	// 		'name' => 'Afhalen',
	// 		'days_of_week_disabled' => '0,6', //comma seperated numbers representing day of week eg: to disable sunder and tuesday: '0,2'
	// 		'delivery_cost' => 0,
	// 		'delivery_limited_to' => null,
	// 		'delivery_radius' => 0, 
	// 		'delivery_radius_from' => 'Berlaarsestraat 10, 2500 Lier',
	// 		'delivery_in_postalcodes' => [],
	// 		'time_required' => false,
	// 		'time_min' => 11, //in hours: 0 - 24
	// 		'time_max' => 19, //in hours: 0 - 24
	// 		'time_default' => '14:00', //string in format HH:mm
	// 	],
	// 	'leveren' 	=> [
	// 		'type' => 'delivery',
	// 		'name' => 'Leveren',
	// 		'days_of_week_disabled' => '0,6',
	// 		'delivery_cost' => 2,
	// 		'delivery_limited_to' => 'postalcode', //null, 'radius', 'postalcode' - for radius you need to fill in delivery.google_maps_api_key
	// 		'delivery_radius' => 10000, //expressed in meters so: 1000 = 1km
	// 		'delivery_radius_from' => 'Berlaarsestraat 10, 2500 Lier',
	// 		'delivery_in_postalcodes' => [
	// 			'2000',
	// 			'2500',
	// 			'2800',
	// 			'2100',
	// 			'2200'
	// 		],
	// 		'time_required' => true,
	// 		'time_min' => 11, //in hours: 0 - 24
	// 		'time_max' => 19, //in hours: 0 - 24
	// 		'time_default' => '14:00', //string in format HH:mm
	// 	]
	// ],

	'datepicker' => [
		'css' => [
			'use' => true,
			'link' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css',
			'asset' => false
		],
		'js' => [
			'use' => true,
			'link' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js',
			'asset' => false,
			'locale' => 'nl-BE',
			'locale_link' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.nl-BE.min.js',
			'locale_asset' => false
		]
	],

	'datetimepicker' => [
		'css' => [
			'use' => true,
			'link' => 'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css',
			'asset' => false
		],
		'js' => [
			'use' => true,
			'link' => 'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js',
			'asset' => false,
			'locale' => 'nl-be' //see https://github.com/moment/moment/tree/develop/locale for valid locales
			
		]
	],

	'moment' => [
		'js' => [
			'use' => true,
			'link' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js',
			'asset' => false
		]
	],

	// 'delivery' => [
	// 	'same_day' => true,
	// 	'same_day_until_hour' => 1,
	// 	'next_day' => true,
	// 	'next_day_until_hour' => 24,
	// 	'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY', ''),
	// ],
	
	'countries_data' => [
        "BE" => [
            "name" => "Belgium",
            "native" => "België",
            "postalcode" => [
                "max" => 4,
                "regex" => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 12,
                'regex' => '^(BE)?0[0-9]{9}$',
                'format' => 'BE0123456789'
            ]
        ],
        "LU" => [
            'name' => 'Luxembourg',
            'native' => 'Luxembourg',
            'postalcode' => [
                'max' => 4,
                'regex' => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(LU)?[0-9]{8}$',
                'format' => 'LU01234567'
            ]
        ],
        "NL" => [
            'name' => 'The Netherlands',
            'native' => 'Nederland',
            'postalcode' => [
                'max' => 8,
                'regex' => '^[1-9][0-9]{3}[ ]?([A-RT-Za-rt-z][A-Za-z]|[sS][BCbcE-Re-rT-Zt-z])$'
            ],
            'vat' => [
                'max' => 14,
                'regex' => '^(NL)?[0-9]{9}B[0-9]{2}$',
                'format' => 'NL001234567B01'
            ]
        ]
    ],

];