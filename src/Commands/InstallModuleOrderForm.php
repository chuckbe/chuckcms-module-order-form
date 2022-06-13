<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;

class InstallModuleOrderForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-order-form:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command installs the ChuckCMS Order Form Module.';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();

        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = 'ChuckCMS Order Form Module';
        $slug = 'chuckcms-module-order-form';
        $hintpath = 'chuckcms-module-order-form';
        $path = 'chuckbe/chuckcms-module-order-form';
        $type = 'module';
        $version = '0.1.15';
        $author = 'Karel Brijs (karel@chuck.be)';

        $json = [];
        $json['admin']['show_in_menu'] = true;
        $json['admin']['menu'] = array(
            'name' => 'Order Form',
            'icon' => 'shopping-cart',
            'route' => '#',
            'has_submenu' => true,
            'submenu' => array(
                'a' => array(
                    'name' => 'Overzicht',
                    'icon' => true,
                    'icon_data' => 'shopping-bag',
                    'route' => 'dashboard.module.order_form.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'b' => array(
                    'name' => 'Bestellingen',
                    'icon' => true,
                    'icon_data' => 'inbox',
                    'route' => 'dashboard.module.order_form.orders.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'c' => array(
                    'name' => 'Producten',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.products.index',
                    'has_submenu' => true,
                    'submenu' => array(
                        'c1' => array(
                            'name' => 'Overzicht',
                            'icon' => true,
                            'icon_data' => 'tag',
                            'route' => 'dashboard.module.order_form.products.index',
                            'has_submenu' => false,
                            'submenu' => null
                        ),
                        'c2' => array(
                            'name' => 'Categorieën',
                            'icon' => true,
                            'icon_data' => 'tag',
                            'route' => 'dashboard.module.order_form.categories.index',
                            'has_submenu' => false,
                            'submenu' => null
                        )
                    )
                ),
                'd' => array(
                    'name' => 'Klanten',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.customers.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'e' => array(
                    'name' => 'Kortingen',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.discounts.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'f' => array(
                    'name' => 'Loyalty',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => '#',
                    'has_submenu' => true,
                    'submenu' => array(
                        'f1' => array(
                            'name' => 'Rewards',
                            'icon' => true,
                            'icon_data' => 'tag',
                            'route' => 'dashboard.module.order_form.rewards.index',
                            'has_submenu' => false,
                            'submenu' => null
                        ),
                        'f2' => array(
                            'name' => 'Coupons',
                            'icon' => true,
                            'icon_data' => 'tag',
                            'route' => 'dashboard.module.order_form.coupons.index',
                            'has_submenu' => false,
                            'submenu' => null
                        )
                    )
                ),
                'g' => array(
                    'name' => 'Locaties',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.locations.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'h' => array(
                    'name' => 'Instellingen',
                    'icon' => true,
                    'icon_data' => 'cpu',
                    'route' => 'dashboard.module.order_form.settings.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'i' => array(
                    'name' => 'POS',
                    'icon' => true,
                    'icon_data' => 'cpu',
                    'route' => 'dashboard.module.order_form.pos.index',
                    'has_submenu' => false,
                    'submenu' => null
                )
            )
        );
        $json['admin']['settings'] = array(
            // 'categories' => array(
            //     'category_1' => array(
            //         'name' => 'Categorie 1',
            //         'is_displayed' => true
            //     ),
            //     'category_2' => array(
            //         'name' => 'Categorie 2',
            //         'is_displayed' => true
            //     ),
            // ),
            'form' => array(
                'display_images' 	=> true,
                'display_description' => true,
                'page' => 'chuckcms-module-order-form::frontend.products.detail'
            ),
            'cart' => array(
                'use_ui' 	=> true
            ),
            'order' => array(
                'has_minimum_order_price' 	=> true,
                'minimum_order_price' => 15,
                'legal_text' => 'Hierbij bevestig ik mijn bestelling en ga ik akkoord met de privacyvoorwaarden.',
                'promo_check' => true,
                'promo_text' => 'Hou mij op de hoogte van de laatste nieuwtjes, openingen en kortingen',
                'payment_upfront' => false,
                'payment_description' => 'Order #',
                'redirect_url' => '/bedankt'
            ),
            'emails' => array(
                'send_confirmation' => true,
                'confirmation_subject' => 'Bevestiging van uw bestelling #',
                'send_notification' => true,
                'notification_subject' => 'Een nieuwe online bestelling #',
                'from_email' => 'hello@chuck.be',
                'from_name' => 'ChuckCMS Order',
                'to_email' => 'hello@chuck.be',
                'to_name' => 'ChuckCMS Order',
                'to_cc' => false, // false or string with emails seperated by comma
                'to_bcc' => false, // false or string with emails seperated by comma
            ),
            // 'locations' => array(
            //     'afhalen' 	=> array(
            //         'type' => 'takeout',
            //         'name' => 'Afhalen',
            //         'days_of_week_disabled' => '0,6', //comma seperated numbers representing day of week eg: to disable sunder and tuesday: '0,2'
            //         'delivery_cost' => 0,
            //         'delivery_limited_to' => null,
            //         'delivery_radius' => 0, 
            //         'delivery_radius_from' => 'Berlaarsestraat 10, 2500 Lier',
            //         'delivery_in_postalcodes' => array(),
            //         'time_required' => false,
            //         'time_min' => 11, //in hours: 0 - 24
            //         'time_max' => 19, //in hours: 0 - 24
            //         'time_default' => '14:00', //string in format HH:mm
            //     ),
            //     'leveren' 	=> array(
            //         'type' => 'delivery',
            //         'name' => 'Leveren',
            //         'days_of_week_disabled' => '0,6',
            //         'delivery_cost' => 2,
            //         'delivery_limited_to' => 'postalcode', //null, 'radius', 'postalcode' - for radius you need to fill in delivery.google_maps_api_key
            //         'delivery_radius' => 10000, //expressed in meters so: 1000 = 1km
            //         'delivery_radius_from' => 'Berlaarsestraat 10, 2500 Lier',
            //         'delivery_in_postalcodes' => array(
            //             '2000',
            //             '2500',
            //             '2800',
            //             '2100',
            //             '2200'
            //         ),
            //         'time_required' => true,
            //         'time_min' => 11, //in hours: 0 - 24
            //         'time_max' => 19, //in hours: 0 - 24
            //         'time_default' => '14:00', //string in format HH:mm
            //     )
            // ),
            'delivery' => array(
                'same_day' => true,
                'same_day_until_hour' => 1,
                'next_day' => true,
                'next_day_until_hour' => 24,
                'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY', ''),
            ),
            'invoice' => array(
                'prefix' => '',
                'number' => 0
            ),
            'pos' => array(
                'ticket_logo' => 'data:image/png;base64,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'
            ),
            
        );

        // create the module
        $module = $this->moduleRepository->createFromArray([
            'name' => $name,
            'slug' => $slug,
            'hintpath' => $hintpath,
            'path' => $path,
            'type' => $type,
            'version' => $version,
            'author' => $author,
            'json' => $json
        ]);

        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('.... AWESOME ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('....   JOB   ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info(' ');
        $this->info('Module installed: ChuckCMS Order Form');
        $this->info(' ');

        
    }
}
