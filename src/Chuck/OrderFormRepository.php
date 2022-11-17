<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\LocationRepository;
use Chuckbe\Chuckcms\Models\FormEntry;
use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Auth;

class OrderFormRepository
{
	private $formEntry;
    private $repeater;

	public function __construct(
        CustomerRepository $customerRepository, 
        LocationRepository $locationRepository, 
        FormEntry $formEntry, 
        Repeater $repeater
    )
    {
        $this->customerRepository = $customerRepository;
        $this->locationRepository = $locationRepository;
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
        $customer = null;

        if (Auth::check() && Auth::user()->hasRole('customer')) {
            $customer = $this->customerRepository->findByUserId(Auth::user()->id);
        }

        return view('chuckcms-module-order-form::frontend.scripts')->with('customer', $customer)->render();
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
        $location = $this->repeater->where('id', $locationId)->first();

        $settings = ChuckSite::module('chuckcms-module-order-form')->settings;

        $from = now()->toDateString();
        $until = now()->addDays(60)->toDateString();
        
        $periodToCheck = $this->getDatesBetween($from, $until);

        foreach ($periodToCheck as $date) {
            if (!$this->locationRepository->isDateAvailable($location, $date, $settings)) {
                continue;
            }

            $now = date_create(now()->format('Y-m-d'));
            $date = date_create($date->format('Y-m-d'));

            return date_diff($now, $date)->format('%a');
        }

        return 0; //@TODO: find alternative for this 
                //maybe loop the function but make sure 
                //it actually ends too and doesn't create 
                //an infinite loop
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
            $total = $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->whereDate('entry->order_date', '>', Carbon::today()->subDays(7))->sum('entry->order_price');
        }
        return number_format((float)$total, 2, ',', '.');
    }

    public function totalSalesLast7DaysQty()
    {
        if(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'paid')->where('entry->order_date', '>', Carbon::today()->subDays(7)->toDateString())->count();
        } else {
            return $this->formEntry->where('slug', config('chuckcms-module-order-form.products.slug'))->where('entry->status', 'awaiting')->where('entry->order_date', '>', Carbon::today()->subDays(7))->count();
        }
    }

    /**
     * Get all dates between two dates.
     *
     * @return \DatePeriod
     */
    public function getDatesBetween($start, $end)
    {
        return $this->getPeriodBetween($start, $end, '1 day');
    }

    /**
     * Get period between two datetimes by given interval.
     *
     * @param $start
     * @param $end
     * @param $interval
     *
     * @return DatePeriod
     */
    public function getPeriodBetween($start, $end, $interval)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        $interval = DateInterval::createFromDateString($interval);
        $period = new DatePeriod($startDate, $interval, $endDate);

        return $period;
    }

}