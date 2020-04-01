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

	'categories' => [
		'category_1' 	=> [
			'name' => 'Categorie 1'
		],
		'category_2' 	=> [
			'name' => 'Categorie 2'
		],
	],

	'form' => [
		'display_images' 	=> true,
		'display_description' => true,
		'page' => 'chuckcms-module-order-form::frontend.products.detail'
	],

	'cart' => [
		'use_ui' 	=> true
	],

	'order' => [
		'has_minimum_order_price' 	=> true,
		'minimum_order_price' => 15,
		'legal_text' => 'Hierbij bevestig ik mijn bestelling en ga ik akkoord met de privacyvoorwaarden.',
		'promo_check' => true,
		'promo_text' => 'Hou mij op de hoogte van de laatste nieuwtjes, openingen en kortingen',
		'payment_upfront' => false,
		'payment_description' => 'Order #',
		'redirect_url' => '/bedankt'
	],

	'emails' => [
		'send_confirmation' => true,
		'confirmation_subject' => 'Bevestiging van uw bestelling #',
		'send_notication' => true,
		'notification_subject' => 'Een nieuwe online bestelling #',
		'from_email' => 'hello@chuck.be',
		'from_name' => 'ChuckCMS Order',
		'to_email' => 'hello@chuck.be',
		'to_name' => 'ChuckCMS Order',
		'to_cc' => false, // false or string with emails seperated by comma
		'to_bcc' => false, // false or string with emails seperated by comma
	],

	'company' => [
		'name' => 'ChuckCMS',
		'vat' => 'BE0XXX.XXX.XXX',
		'address1' => 'Berlaarsestraat 10',
		'address2' => '2500 Lier',
		'email' => 'hello@chuck.be'
	],

	'locations' => [
		'afhalen' 	=> [
			'type' => 'takeout',
			'name' => 'Afhalen',
			'days_of_week_disabled' => '0,6', //comma seperated numbers representing day of week eg: to disable sunder and tuesday: '0,2'
		],
		'leveren' 	=> [
			'type' => 'delivery',
			'name' => 'Leveren',
			'days_of_week_disabled' => '1,2',
		]
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

	'delivery' => [
		'same_day' => true,
		'same_day_until_hour' => 15,
		'next_day' => true,
		'next_day_until_hour' => 15,
	],

];