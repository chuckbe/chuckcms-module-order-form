<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\FormEntry;
use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderFormRepository
{
	private $formEntry;
    private $repeater;

	public function __construct(FormEntry $formEntry, Repeater $repeater)
    {
        $this->formEntry = $formEntry;
        $this->repeater = $repeater;
    }

    /**
     * Get all the products
     *
     * @var string
     **/
    private function getProducts()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.products.slug'))->get();
    }

    public function render()
    {
        $products = $this->getProducts();
    	return view('chuckcms-module-order-form::frontend.form', compact('products'))->render();
    }

    public function scripts()
    {
        return view('chuckcms-module-order-form::frontend.scripts')->render();
    }

    public function styles()
    {
        return view('chuckcms-module-order-form::frontend.css')->render();
    }

    public function firstAvailableDate(string $locationKey)
    {
        return date('d/m/Y', strtotime('+' . $this->firstAvailableDateInDaysFromNow($locationKey) . ' day'));
    }

    public function firstAvailableDateInDaysFromNow(string $locationKey)
    {
        $initial_day = 0;
        $days_of_week_disabled = config('chuckcms-module-order-form.locations.'.$locationKey.'.days_of_week_disabled');

        if(config('chuckcms-module-order-form.delivery.same_day') == true) {
            $until_hour = config('chuckcms-module-order-form.delivery.same_day_until_hour');
        } elseif (config('chuckcms-module-order-form.delivery.next_day') == true) {
            $until_hour = config('chuckcms-module-order-form.delivery.next_day_until_hour');
            $initial_day = $initial_day + 1;
        }

        if(date('H') < $until_hour) {
            $starting_day = $initial_day;
        } elseif (date('H') >= $until_hour) {
            $starting_day = $initial_day + 1;
        }

        if($days_of_week_disabled == '') {
            return (string) $starting_day;
        }

        if($starting_day == 0 && strpos($days_of_week_disabled, date('N')) !== false) {
            return '0';
        }

        for ($i=$starting_day; $i < 8; $i++) { 
            if(strpos($days_of_week_disabled, date('N', strtotime('+'.$i.' day'))) === false) {
                return ''.$i.'';
            }
        }
    }

    public function followup($order_number)
    {
        $order = $this->formEntry->where('entry->order_number', $order_number)->first();
        return view('chuckcms-module-order-form::frontend.followup', compact('order'))->render();
    }

    public function followupScripts($order_number)
    {
        $order = $this->formEntry->where('entry->order_number', $order_number)->first();
        return view('chuckcms-module-order-form::frontend.followup_scripts', compact('order'))->render();
    }

    public function followupStyles($order_number)
    {
        return view('chuckcms-module-order-form::frontend.followup_css')->render();
    }

    public function totalSales()
    {
        if(config('chuckcms-module-order-form.order.payment_upfront')) {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->sum('entry->order_price');
        } else {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->sum('entry->order_price');
        }
        return number_format((float)$total, 2, ',', '.');
    }

    public function totalSalesLast7Days()
    {
        if(config('chuckcms-module-order-form.order.payment_upfront')) {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->sum('entry->order_price');
        } else {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->sum('entry->order_price');
        }
        return number_format((float)$total, 2, ',', '.');
    }

    public function totalSalesLast7DaysQty()
    {
        if(config('chuckcms-module-order-form.order.payment_upfront')) {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->count();
        } else {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->count();
        }
    }

}