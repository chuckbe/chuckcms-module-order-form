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
                'first' => array(
                    'name' => 'Overzicht',
                    'icon' => true,
                    'icon_data' => 'shopping-bag',
                    'route' => 'dashboard.module.order_form.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'second' => array(
                    'name' => 'Bestellingen',
                    'icon' => true,
                    'icon_data' => 'inbox',
                    'route' => 'dashboard.module.order_form.orders.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'third' => array(
                    'name' => 'Categorieën',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.categories.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'fourth' => array(
                    'name' => 'Producten',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.products.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'fifth' => array(
                    'name' => 'Locaties',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.order_form.locations.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'sixth' => array(
                    'name' => 'Instellingen',
                    'icon' => true,
                    'icon_data' => 'cpu',
                    'route' => 'dashboard.module.order_form.settings.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'seventh' => array(
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
