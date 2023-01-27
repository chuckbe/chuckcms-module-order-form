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

	'promo' => [
		'action' => null,
		'followup' => null,
	],

	'order' => [
		'followup' => null
	],

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