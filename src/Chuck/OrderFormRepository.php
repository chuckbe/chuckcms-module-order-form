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

    public function firstAvailableDate($locationId)
    {
        return date('d/m/Y', strtotime('+' . $this->firstAvailableDateInDaysFromNow($locationId) . ' day'));
    }

    public function firstAvailableDateInDaysFromNow($locationId)
    {
        $initial_day = 0;
        $location = $this->repeater->where('id', $locationId)->first();
        $days_of_week_disabled = is_null($location->days_of_week_disabled) ? '' : $location->days_of_week_disabled;
        
        $settings = ChuckSite::module('chuckcms-module-order-form')->settings;
        //is same day delivery possible?
        if($settings['delivery']['same_day'] == true) {
            //yes see until what hour
            $until_hour = $settings['delivery']['same_day_until_hour']; /// 15 (now: 18)
            if(date('H') < $until_hour) { ////////// fail (18 < 15)
                //nice, same day delivery is possible and it's not too late
                $starting_day = $initial_day;
            } elseif (date('H') >= $until_hour) { ////////// pass (18 >= 15)
                //oh snap, we're too late for same day delivery...
                //let's see if next_day delivery is possible
                if ($settings['delivery']['next_day'] == true) {
                    //yay next day delivery is possible, let's see until what hour
                    $nd_until_hour = $settings['delivery']['next_day_until_hour'];
                    if(date('H') < $nd_until_hour) { ////////// fail (18 < 15)
                        //nice, not too late for next day delivery
                        $starting_day = $initial_day + 1;
                    } elseif (date('H') >= $nd_until_hour) {
                        //snap, too late for next day delivery so set the day after tomorrow
                        $starting_day = $initial_day + 2;
                    }
                } else {
                    //Snap, too late for same day delivery and no orders for next day deliveries so set the day after tomorrow
                    $starting_day = $initial_day + 2;
                }
            }
        } elseif ($settings['delivery']['next_day'] == true) {
            //woops, same day deliveries not possible, but yay next day deliveries are, let's see until when
            $nd_until_hour = $settings['delivery']['next_day_until_hour'];
            if(date('H') < $nd_until_hour) { ////////// fail (18 < 15)
                //nice, just in time for next day delivery
                $starting_day = $initial_day + 1;
            } elseif (date('H') >= $nd_until_hour) {
                //snap, too late for next day delivery so set the day after tomorrow
                $starting_day = $initial_day + 2;
            }
        } else {
            //damn, nor same day nor next day deliveries possible so set the day after tomorrow
            $starting_day = $initial_day + 2;
        }

        if($days_of_week_disabled == '') {
            return (string) $starting_day;
        }

        if($starting_day == 0 && strpos($days_of_week_disabled, date('w')) !== false) {
            return '0';
        }

        for ($i=$starting_day; $i < 9; $i++) { 
            if(strpos($days_of_week_disabled, date('w', strtotime('+'.$i.' day'))) === false) {
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
        if(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->sum('entry->order_price');
        } else {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->sum('entry->order_price');
        }
        return number_format((float)$total, 2, ',', '.');
    }

    public function totalSalesLast7Days()
    {
        if(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->whereDate('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->sum('entry->order_price');
        } else {
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->whereDate('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->sum('entry->order_price');
        }
        return number_format((float)$total, 2, ',', '.');
    }

    public function totalSalesLast7DaysQty()
    {
        if(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->count();
        } else {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->count();
        }
    }

}