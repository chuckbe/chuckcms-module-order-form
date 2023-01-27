<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\LocationRepository;

use Chuckbe\ChuckcmsModuleOrderForm\Requests\Order\CreateOrderRequest;

use Chuckbe\Chuckcms\Models\FormEntry;
use Chuckbe\Chuckcms\Models\Repeater;

use ChuckSite;

use Mollie;
use Str;
use PDF;
use Mail;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Auth;

class OrderRepository
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
     * Create the order.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateOrderRequest $request)
    {
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

    private function generatePDF($order)
    {
        if (! array_key_exists('invoice', $order->entry)) {
            return;
        }
        
        $pdf = PDF::loadView('chuckcms-module-order-form::pdf.invoice', compact('order'));
        return $pdf->output();
    }
}