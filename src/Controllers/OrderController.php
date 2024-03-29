<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\FormEntry;
use ChuckSite;
use Mollie;
use URL;
use Str;
use PDF;
use Mail;
use Illuminate\Support\Carbon;
use DatePeriod;
use DateTime;
use DateInterval;
use ChuckRepeater;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\DiscountRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\SettingsRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    protected $discountRepository;
    protected $settingsRepository;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DiscountRepository $discountRepository, CustomerRepository $customerRepository, SettingsRepository $settingsRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->customerRepository = $customerRepository;
        $this->settingsRepository = $settingsRepository;
    }

    public function index()
    {
        if (!request()->has('date')) {
            return redirect()->route('dashboard.module.order_form.orders.index', ['date' => date('Y-m-d', strtotime(now()->subDays(1)))]);
        }

        $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))->orderByDesc('created_at');

        

        $dates = explode(',', request()->date);
        $startDate = $dates[0];
        $endDate = count($dates) > 1 ? $dates[1] : null;

        $selectedLocation = !request()->has('location') ? null : ChuckRepeater::find(request()->get('location'));

        $type = !request()->has('type') ? null : request()->get('type');
        $status = !request()->has('status') ? null : request()->get('status');


        if (!is_null($selectedLocation)) {
            $orders = $orders->where('entry->location', $selectedLocation->id);
        }

        if (!is_null($type)) {
            $orders = $orders->where('entry->type', request()->get('type'));
        }

        if (!is_null($status)) {
            $orders = $orders->where('entry->status', request()->get('status'));
        }

        if (is_null($endDate)) {
            $d = explode('-', $startDate)[2];
            $m = explode('-', $startDate)[1];
            $y = explode('-', $startDate)[0];

            $orders = $orders->where('entry->order_date', $d.'/'.$m.'/'.$y);
            
        }

        if (!is_null($endDate)) {
            $datesArray = $this->getArrayForDates($startDate, $endDate);
            $orders = $orders->whereIn('entry->order_date', $datesArray);
        }

        $orders = $orders->get();

        $total = $orders->sum(function ($order) {
            return round($order->entry['order_price'], 2);
        });

        return view('chuckcms-module-order-form::backend.orders.index', compact('orders', 'startDate', 'endDate', 'selectedLocation', 'type', 'status', 'total'));
    }

    public function detail(FormEntry $order)
    {
        return view('chuckcms-module-order-form::backend.orders.detail', compact('order'));
    }

    public function excel() 
    {
        if (!request()->has('date')) {
            return redirect()->route('dashboard.module.order_form.orders.excel', ['date' => date('Y-m-d', strtotime(now()->subDays(1)))]);
        }

        $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))->orderByDesc('created_at');

        $dates = explode(',', request()->date);
        $startDate = $dates[0];
        $endDate = count($dates) > 1 ? $dates[1] : null;

        $selectedLocation = !request()->has('location') ? null : ChuckRepeater::find(request()->get('location'));

        $type = !request()->has('type') ? null : request()->get('type');
        $status = !request()->has('status') ? null : request()->get('status');

        if (!is_null($selectedLocation)) {
            $orders = $orders->where('entry->location', $selectedLocation->id);
        }

        if (!is_null($type)) {
            $orders = $orders->where('entry->type', request()->get('type'));
        }

        if (!is_null($status)) {
            $orders = $orders->where('entry->status', request()->get('status'));
        }

        if (is_null($endDate)) {
            $d = explode('-', $startDate)[2];
            $m = explode('-', $startDate)[1];
            $y = explode('-', $startDate)[0];

            $orders = $orders->where('entry->order_date', $d.'/'.$m.'/'.$y);
        }

        if (!is_null($endDate)) {
            $datesArray = $this->getArrayForDates($startDate, $endDate);
            $orders = $orders->whereIn('entry->order_date', $datesArray);
        }

        $orders = $orders->get();

        $fileName = Str::slug(ChuckSite::getSite('name'), '_').'_orders_export.xlsx';
        return Excel::download(new OrdersExport($orders), $fileName);
    }

    public function pdf()
    {
        ini_set('max_execution_time', 900);
        
        if (!request()->has('date')) {
            return redirect()->route('dashboard.module.order_form.orders.pdf', ['date' => date('Y-m-d', strtotime(now()->subDays(1)))]);
        }

        $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))->orderByDesc('created_at');

        $dates = explode(',', request()->date);
        $startDate = $dates[0];
        $endDate = count($dates) > 1 ? $dates[1] : null;

        $selectedLocation = !request()->has('location') ? null : ChuckRepeater::find(request()->get('location'));

        $type = !request()->has('type') ? null : request()->get('type');
        $status = !request()->has('status') ? null : request()->get('status');

        if (!is_null($selectedLocation)) {
            $orders = $orders->where('entry->location', $selectedLocation->id);
        }

        if (!is_null($type)) {
            $orders = $orders->where('entry->type', request()->get('type'));
        }

        if (!is_null($status)) {
            $orders = $orders->where('entry->status', request()->get('status'));
        }

        if (is_null($endDate)) {
            $d = explode('-', $startDate)[2];
            $m = explode('-', $startDate)[1];
            $y = explode('-', $startDate)[0];

            $orders = $orders->where('entry->order_date', $d.'/'.$m.'/'.$y);
        }

        if (!is_null($endDate)) {
            $datesArray = $this->getArrayForDates($startDate, $endDate);
            $orders = $orders->whereIn('entry->order_date', $datesArray);
        }

        $orders = $orders->get();

        $pdf = \PDF::loadView('chuckcms-module-order-form::backend.orders.pdf.all', compact('orders'));
        $fileName = Str::slug(ChuckSite::getSite('name'), '_').'_orders_pdf_export.pdf';

        return $pdf->download($fileName);
    }

    public function updateDate(Request $request)
    {
        $this->validate(request(), [
            'order_date' => 'required'
        ]);

        $id = $request['edit_order_id'];
        $order = FormEntry::where('id', $id)->first();

        $entry = $order->entry;
        $entry['order_date'] = $request['order_date'];
        $order->entry = $entry;
        if ( $order->save() ) {
            return redirect()->route('dashboard.module.order_form.orders.detail', ['order' => $order->id])->with('notification', 'Datum gewijzigd!');
        } 

        return redirect()->route('dashboard.module.order_form.orders.detail', ['order' => $order->id]);
    }

    public function updateAddress(Request $request)
    {
        $this->validate(request(), [
            'street' => 'required',
            'housenumber' => 'required',
            'postalcode' => 'required',
            'city' => 'required',
        ]);

        $id = $request['edit_order_id'];
        $order = FormEntry::where('id', $id)->first();
        
        $entry = $order->entry;
        $entry['street'] = $request['street'];
        $entry['housenumber'] = $request['housenumber'];
        $entry['postalcode'] = $request['postalcode'];
        $entry['city'] = $request['city'];
        $order->entry = $entry;
        if ( $order->save() ) {
            return redirect()->route('dashboard.module.order_form.orders.detail', ['order' => $order->id])->with('notification', 'Adres gewijzigd!');
        } 

        return redirect()->route('dashboard.module.order_form.orders.detail', ['order' => $order->id]);
    }

    /**
     * Store the order in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function postOrder(Request $request)
    {
        $this->validate(request(), [
            'location' => 'required',
            'order_date' => 'required',
            'surname' => 'required|max:155',
            'name' => 'required|max:155',
            'email' => 'email|required',
            'tel' => 'nullable',
            'street' => 'required',
            'housenumber' => 'required|max:10',
            'postalcode' => 'required|max:4',
            'city' => 'required',
            'remarks' => 'nullable',
            'invoice' => 'required',
            'company' => 'nullable',
            'vat' => 'nullable',
            'invoice_street' => 'nullable',
            'invoice_housenumber' => 'nullable|max:10',
            'invoice_postalcode' => 'nullable|max:4',
            'invoice_city' => 'nullable',
            'order' => 'required',
            'total' => 'required',
            'shipping' => 'required',
            'legal_approval' => 'required',
            'promo_approval' => 'nullable'
        ]);

        $order = new FormEntry();
        $order->slug = config('chuckcms-module-order-form.products.slug');
        
        $all_json = [];

        
        $all_json['order_number'] = str_random(8);
        $all_json['status'] = 'awaiting';
        $all_json['type'] = 'web';
        $all_json['first_name'] = $request['surname'];
        $all_json['last_name'] = $request['name'];
        $all_json['email'] = $request['email'];
        $all_json['tel'] = $request['tel'];
        $all_json['street'] = $request['street'];
        $all_json['housenumber'] = $request['housenumber'];
        $all_json['postalcode'] = $request['postalcode'];
        $all_json['city'] = $request['city'];
        $all_json['remarks'] = $request['remarks'];

        if ($request['invoice']) {
            $all_json['company'] = $request['company'];
            $all_json['vat'] = $request['vat'];
            $all_json['invoice_street'] = !is_null($request['invoice_street'])
                                         ? $request['invoice_street'] 
                                         : $request['street'];
            $all_json['invoice_housenumber'] = !is_null($request['invoice_housenumber'])
                                         ? $request['invoice_housenumber'] 
                                         : $request['housenumber'];
            $all_json['invoice_postalcode'] = !is_null($request['invoice_postalcode'])
                                         ? $request['invoice_postalcode'] 
                                         : $request['postalcode'];
            $all_json['invoice_city'] = !is_null($request['invoice_city'])
                                         ? $request['invoice_city'] 
                                         : $request['city'];
        }

        $all_json['location'] = $request['location'];
        $all_json['order_date'] = $request['order_date'];
        $all_json['order_time'] = $request['order_time'];

        $all_json['order_subtotal'] = round($request['subtotal'], 2);
        $all_json['order_discount'] = round($request['discount'], 2);
        
        $all_json['order_price'] = round($request['total'], 2);
        $all_json['order_shipping'] = round($request['shipping'], 2);
        $all_json['order_price_with_shipping'] = round(($request['total'] + $request['shipping']), 2);

        if($request['total'] < ChuckSite::module('chuckcms-module-order-form')->getSetting('order.minimum_order_price')) {
            return response()->json([
                'status' => 'error'
            ]);
        }
        
        $items = [];

        foreach($request['order'] as $product){
            $item = [];
            $prodKey = $product['product_id'];
            //we only need the id and the qty here then we can get everything from products
            $item['id'] = $prodKey;
            $item['name'] = $product['name'];
            $item['price'] = $product['price'];
            $item['qty'] = $product['qty'];
            $item['totprice'] = round($product['totprice'], 2);

            if($product['attributes'] == 'false'){
                $item['attributes'] = false;
            } else {
                $item['attributes'] = $product['attributes'];
            }

            if($product['options'] !== false){
                $item['options'] = json_decode($product['options']);
            } else {
                $item['options'] = false;
            }

            if($product['extras'] !== false){
                $item_extras = json_decode($product['extras']);
                $extras = [];
                if($item_extras !== false) {
                    foreach($item_extras as $item_extra) {
                        if(count((array)$item_extra) > 0) {
                            $extras[] = $item_extra;
                        }
                    }
                }

                if(count($extras) > 0) {
                    $item['extras'] = $extras;
                } else {
                    $item['extras'] = false;
                }
            } else {
                $item['extras'] = false;
            }

            if($product['subproducts'] !== false){
                
                $item['subproducts'] = json_decode($product['subproducts']);

            } else {
                $item['subproducts'] = false;
            }

            if(array_key_exists('discounts', $product)) {
                $item['discounts'] = $product['discounts'];
            }

            if(array_key_exists('discount', $product)) {
                $item['discount'] = $product['discount'];
            }

            $items[] = $item;
            
        }

        $all_json['items'] = $items;

        $discounts = [];
        if (is_array($request['discounts']) && count($request['discounts']) > 0) {
            foreach($request['discounts'] as $singleDiscount){
                $discount = [];
                $discount['id'] = $singleDiscount['id'];

                $discounts[] = $discount;
            }
        }

        $all_json['discounts'] = $discounts;

        $order->entry = $all_json;

        if($order->save()){

            if (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.promo_check')
                && $request['promo_approval'] !== null && $request['promo_approval'] !== 0) {
                $json = $order->entry;
                $json['promo_check'] = true;
                $order->entry = $json;
                $order->update();

                $promoClassName = config('chuckcms-module-order-form.promo.action');

                if (! is_null($promoClassName)) {
                    $promoClass = new $promoClassName;

                    if (method_exists($promoClass, 'action')) {
                        try {
                            $promoClass->action($order);
                        } catch (\Exception $e) {
                            //void
                        }
                    }
                }
            }

            if(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront')) {
                $amount = number_format( ( (float)$order->entry['order_price_with_shipping'] ), 2, '.', '');

                $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => $amount, // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_description') . $order->entry['order_number'],
                'webhookUrl' => route('cof.mollie_webhook'),
                'redirectUrl' => route('cof.followup', ['order_number' => $order->entry['order_number']]),
                "metadata" => array(
                    'amount' => $amount,
                    'order_id' => $order->id,
                    'order_number' => $order->entry['order_number']
                    )
                ]);

                $order_json = $order->entry;
                $order_json['payment_id'] = $payment->id;
                $order->entry = $order_json;
                $order->save();

                $payment = Mollie::api()->payments()->get($payment->id);

                // redirect customer to Mollie checkout page
                return response()->json([
                    'status' => 'success',
                    'url' => $payment->getCheckoutUrl()
                ]);
                
            } else {
                //No Payment upfront so send confirmation
                $this->sendConfirmation($order);
                //@todo : sendNotification - printer
                $this->sendNotification($order);
                return response()->json([
                    'status' => 'success',
                    'url' => route('cof.followup', ['order_number' => $order->entry['order_number']])
                ]);
            }
            

        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function orderFollowup($order_number)
    {
        $order = FormEntry::where('entry->order_number', $order_number)->first();

        if($order == null) {
            return abort(404);
        } else {
            $this->followupActions($order);

            return redirect(URL::to(ChuckSite::module('chuckcms-module-order-form')->getSetting('order.redirect_url')), 303)->with('order_number', $order_number);
        }

    }

    public function followupActions(FormEntry $order)
    {
        if (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.promo_check')
            && array_key_exists('promo_check', $order->entry) && $order->entry['promo_check'] == true) {
            $promoClassName = config('chuckcms-module-order-form.promo.followup');

            if (! is_null($promoClassName)) {
                $promoClass = new $promoClassName;

                if (method_exists($promoClass, 'followup')) {
                    try {
                        $promoClass->followup($order);
                    } catch (\Exception $e) {
                        //void
                    }
                }
            }
        }

        $orderFollowupClassName = config('chuckcms-module-order-form.order.followup');

        if (! is_null($orderFollowupClassName)) {
            $orderFollowupClass = new $orderFollowupClassName;

            if (method_exists($orderFollowupClass, 'followup')) {
                try {
                    $orderFollowupClass->followup($order);
                } catch (\Exception $e) {
                    //void
                }
            }
        }

        //backup incase webhook doesnt work
        if (! array_key_exists('invoice', $order->entry) 
            && array_key_exists('company', $order->entry)
            && $order->entry['status'] == 'paid') {
            $this->generateInvoice($order);
        }
    }



    public function orderStatus(Request $request)
    {
        $order_number = $request['order_number'];
        $order = FormEntry::where('entry->order_number', $order_number)->first();
        
        return response()->json([
            'status' => $order->entry['status']
        ]);     
    }



    public function orderPay($order_number)
    {
        $order = FormEntry::where('entry->order_number', $order_number)->first();

        $mollie = Mollie::api()->payments()->get($order->entry['payment_id']);

        if ($mollie->isPaid()) {
            $json = $order->entry;
            $json['status'] = 'paid';
            $order->entry = $json;
            $order->save();
            $order = $order->fresh();

            if (! array_key_exists('invoice', $order->entry) 
                && array_key_exists('company', $order->entry)
                && $order->entry['status'] == 'paid') {
                $this->generateInvoice($order);
            }
            
            $this->sendNotification($order);
            $this->sendConfirmation($order);

            return redirect()->route('cof.followup', ['order_number' => $order_number]);
        }
        
        if($order->entry['status'] == 'paid') {
            return redirect()->route('cof.followup', ['order_number' => $order_number]);
        }

        $amount = number_format( ( (float)$order->entry['order_price_with_shipping'] ), 2, '.', '');

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $amount, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_description') . $order->entry['order_number'],
            'webhookUrl' => route('cof.mollie_webhook'),
            'redirectUrl' => route('cof.followup', ['order_number' => $order->entry['order_number']]),
            "metadata" => array(
                'amount' => $amount,
                'order_id' => $order->id,
                'order_number' => $order->entry['order_number']
                )
        ]);

        $payment = Mollie::api()->payments()->get($payment->id);

        $order_json = $order->entry;
        $order_json['old_payment_id'] = $order_json['payment_id'];
        $order_json['payment_id'] = $payment->id;
        $order->entry = $order_json;
        $order->save(); 

        return redirect($payment->getCheckoutUrl(), 303);
    }





    public function webhookMollie(Request $request)
    {
        if (! $request->has('id')) {
            return;
        }

        $payment = Mollie::api()->payments()->get($request->id);
        $order = FormEntry::where('id', $payment->metadata->order_id)->where('entry->order_number', $payment->metadata->order_number)->first();
        if($order == null) {
            return response()->json(['status' => 'success'], 200);
        }
        if ($payment->isPaid()) {
            $json = $order->entry;
            $json['status'] = 'paid';
            $order->entry = $json;
            $order->save();
            $order = $order->fresh();

            if (! array_key_exists('invoice', $order->entry) 
                && array_key_exists('company', $order->entry)
                && $order->entry['status'] == 'paid') {
                $this->generateInvoice($order);
            }
            
            $this->sendNotification($order);
            $this->sendConfirmation($order);
        } else {
            if (array_key_exists('old_payment_id', $order->entry)) {
                $mollie = Mollie::api()->payments()->get($order->entry['old_payment_id']);

                if ($mollie->isPaid()) {
                    $json = $order->entry;
                    $json['status'] = 'paid';
                    $order->entry = $json;
                    $order->save();
                    $order = $order->fresh();

                    if (! array_key_exists('invoice', $order->entry) 
                        && array_key_exists('company', $order->entry)
                        && $order->entry['status'] == 'paid') {
                        $this->generateInvoice($order);
                    }
                    
                    $this->sendNotification($order);
                    $this->sendConfirmation($order);
                }
            }
            if($order->entry['status'] !== 'paid') {
                $json = $order->entry;
                $json['status'] = $payment->status;
                $order->entry = $json;
                $order->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function resendConfirmation(Request $request)
    {
        $this->validate(request(), [
            'order_id' => 'required'
        ]);

        $order = FormEntry::where('id', $request->order_id)->first();

        if (is_null($order)) {
            return response()->json(['status' => 'error'], 404);
        }

        $this->sendConfirmation($order);

        return response()->json(['status' => 'success'], 200);
    }

    public function sendConfirmation(FormEntry $order)
    {
        if( (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront') && $order->entry['status'] == 'paid') || (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront') == false) ){
            $pdf = null;

            if (array_key_exists('invoice', $order->entry)) {
                $pdf = $this->generatePDF($order);
            } 

            Mail::send('chuckcms-module-order-form::frontend.emails.confirmation', ['order' => $order], function ($m) use ($order, $pdf) {
                $m->from(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.from_email'), ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.from_name'));
                $m->to($order->entry['email'], $order->entry['first_name'].' '.$order->entry['last_name'])->subject(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.confirmation_subject').$order->entry['order_number']);

                if (!is_null($pdf)) {
                    $m->attachData($pdf, $order->entry['invoice'].'.pdf', ['mime' => 'application/pdf']);
                }
            });
        }
    }

    public function sendNotification(FormEntry $order)
    {
        if( (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront') && $order->entry['status'] == 'paid') || (ChuckSite::module('chuckcms-module-order-form')->getSetting('order.payment_upfront') == false) ){
            $pdf = null;

            if (array_key_exists('invoice', $order->entry)) {
                $pdf = $this->generatePDF($order);
            } 

            Mail::send('chuckcms-module-order-form::frontend.emails.notification', ['order' => $order], function ($m) use ($order, $pdf) {
                $m->from(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.from_email'), ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.from_name'));
                $m->to(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_email'), ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_name'))->subject(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.notification_subject').$order->entry['order_number']);
                
                if( ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_cc') !== false && !is_null(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_cc'))){
                    $m->cc(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_cc'));
                }

                if( ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_bcc') !== false && !is_null(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_bcc'))){
                    $m->bcc(ChuckSite::module('chuckcms-module-order-form')->getSetting('emails.to_bcc'));
                }

                if (!is_null($pdf)) {
                    $m->attachData($pdf, $order->entry['invoice'].'.pdf', ['mime' => 'application/pdf']);
                }
            });
        }
    }

    public function getArrayForDates($startDate, $endDate)
    {
        if ($startDate == $endDate) {
            $d = explode('-', $startDate)[2];
            $m = explode('-', $startDate)[1];
            $y = explode('-', $startDate)[0];

            return [$d.'/'.$m.'/'.$y];
        }

        $period = new DatePeriod(
             new DateTime($startDate),
             new DateInterval('P1D'),
             new DateTime(Carbon::parse($endDate)->addDays()->toDateString())
        );

        $dates = [];
        foreach ($period as $key => $date) {
            $dates[] = $date->format('d/m/Y');       
        }

        return $dates;
    }

    public function checkDiscountCode(Request $request)
    {
        $this->validate(request(), [
            'code' => 'required'
        ]);

        $discount = $this->discountRepository->code($request->code);

        if (is_null($discount)) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json(['status' => 'success', 'discount' => $discount]);
    }

    private function generateInvoice($order)
    {
        $module = $this->settingsRepository->get();
        $json = $module->json;
        $settings = $json['admin']['settings'];
        $prefix = array_key_exists('prefix', $settings['invoice'])
            ? $settings['invoice']['prefix']
            : '';
        $invoiceNumber = array_key_exists('prefix', $settings['invoice'])
            ? intval($settings['invoice']['number']) + 1
            : 1;

        $orderEntry = $order->entry;
        $orderEntry['invoice'] = $prefix.str_pad($invoiceNumber, 4, '0', STR_PAD_LEFT);
        $orderEntry['invoice_date'] = now()->format('d/m/Y');

        $order->entry = $orderEntry;
        $order->save();

        $settings['invoice']['prefix'] = $prefix;
        $settings['invoice']['number'] = $invoiceNumber;
        $json['admin']['settings'] = $settings;
        $module->json = $json;
        $module->update();
    }

    private function generatePDF($order)
    {
        if (! array_key_exists('invoice', $order->entry)) {
            return;
        }
        
        $pdf = PDF::loadView('chuckcms-module-order-form::pdf.invoice', compact('order'));
        return $pdf->output();
    }

    public function downloadInvoice(FormEntry $order)
    {
        if (! array_key_exists('invoice', $order->entry)) {
            return;
        }

        $pdf = PDF::loadView('chuckcms-module-order-form::pdf.invoice', compact('order'));
        return $pdf->download($order->entry['invoice'].'.pdf');
    }
}
