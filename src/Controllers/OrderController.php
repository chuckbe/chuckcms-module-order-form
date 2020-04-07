<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\FormEntry;
use Mollie;
use URL;
use Mail;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        $orders = FormEntry::where('slug', config('chuckcms-module-order-form.products.slug'))->get();
        return view('chuckcms-module-order-form::backend.orders.index', compact('orders'));
    }

    public function detail(FormEntry $order)
    {
        return view('chuckcms-module-order-form::backend.orders.detail', compact('order'));
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
        $all_json['first_name'] = $request['surname'];
        $all_json['last_name'] = $request['name'];
        $all_json['email'] = $request['email'];
        $all_json['tel'] = $request['tel'];
        $all_json['street'] = $request['street'];
        $all_json['housenumber'] = $request['housenumber'];
        $all_json['postalcode'] = $request['postalcode'];
        $all_json['city'] = $request['city'];

        $all_json['location'] = $request['location'];
        $all_json['order_date'] = $request['order_date'];
        $all_json['order_time'] = $request['order_time'];
        $all_json['order_price'] = round($request['total'], 2);
        $all_json['order_shipping'] = round($request['shipping'], 2);
        $all_json['order_price_with_shipping'] = round(($request['total'] + $request['shipping']), 2);
        
        $items = [];

        foreach($request['order'] as $product){
            $item = [];
            $prodKey = $product['product_id'];
            
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

            $items[] = $item;
            
        }

        $all_json['items'] = $items;


        // foreach($request['order'] as $prodKey => $prodVal){
        //     if($prodVal['attributes'] == 'false'){
        //         $all_json['items'][$prodKey]['attributes'] = false;
        //         $all_json['items'][$prodKey]['name'] = $prodVal['name'];
        //         $all_json['items'][$prodKey]['price'] = $prodVal['price'];
        //         $all_json['items'][$prodKey]['qty'] = $prodVal['qty'];
        //         $all_json['items'][$prodKey]['totprice'] = round($prodVal['totprice'], 2);
        //     }
        //     if($prodVal['attributes'] == 'true'){
        //         $all_json['items'][$prodKey]['attributes'] = true;
        //         foreach($prodVal as $attrKey => $attrVal){
        //             if($attrKey !== 'attributes'){
        //                     $all_json['items'][$prodKey]['attributes_list'][$attrKey]['name'] = $attrVal['name'];
        //                 $all_json['items'][$prodKey]['attributes_list'][$attrKey]['price'] = $attrVal['price'];
        //                 $all_json['items'][$prodKey]['attributes_list'][$attrKey]['qty'] = $attrVal['qty'];
        //                 $all_json['items'][$prodKey]['attributes_list'][$attrKey]['totprice'] = round($attrVal['totprice'], 2);
        //             }
        //         }
        //     }
            
        // }

        $order->entry = $all_json;

        if($order->save()){

            if(config('chuckcms-module-order-form.order.payment_upfront')) {
                $amount = number_format( ( (float)$order->entry['order_price'] ), 2, '.', '');

                $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => $amount, // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => config('chuckcms-module-order-form.order.payment_description') . $order->entry['order_number'],
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
            return redirect(URL::to(config('chuckcms-module-order-form.order.redirect_url')), 303)->with('order_number', $order_number);
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
        
        if($order->entry['status'] == 'paid') {
            return redirect()->route('cof.followup', ['order_number' => $order_number]);
        }

        $amount = number_format( ( (float)$order->entry['order_price'] ), 2, '.', '');

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $amount, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => config('chuckcms-module-order-form.order.payment_description') . $order->entry['order_number'],
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
            
            $this->sendNotification($order);
            $this->sendConfirmation($order);
        } else {
            $json = $order->entry;
            $json['status'] = $payment->status;
            $order->entry = $json;
            $order->save();
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function sendConfirmation(FormEntry $order)
    {
        if( (config('chuckcms-module-order-form.order.payment_upfront') && $order->entry['status'] == 'paid') || (config('chuckcms-module-order-form.order.payment_upfront') == false) ){
            Mail::send('chuckcms-module-order-form::frontend.emails.confirmation', ['order' => $order], function ($m) use ($order) {
                $m->from(config('chuckcms-module-order-form.emails.from_email'), config('chuckcms-module-order-form.emails.from_name'));
                $m->to($order->entry['email'], $order->entry['first_name'].' '.$order->entry['last_name'])->subject(config('chuckcms-module-order-form.emails.confirmation_subject').$order->entry['order_number']);
            });
        }
    }

    public function sendNotification(FormEntry $order)
    {
        if( (config('chuckcms-module-order-form.order.payment_upfront') && $order->entry['status'] == 'paid') || (config('chuckcms-module-order-form.order.payment_upfront') == false) ){
            Mail::send('chuckcms-module-order-form::frontend.emails.notification', ['order' => $order], function ($m) use ($order) {
                $m->from(config('chuckcms-module-order-form.emails.from_email'), config('chuckcms-module-order-form.emails.from_name'));
                $m->to(config('chuckcms-module-order-form.emails.to_email'), config('chuckcms-module-order-form.emails.to_name'))->subject(config('chuckcms-module-order-form.emails.notification_subject').$order->entry['order_number']);
                
                if( config('chuckcms-module-order-form.emails.to_cc') !== false){
                    $m->cc(config('chuckcms-module-order-form.emails.to_cc'));
                }

                if( config('chuckcms-module-order-form.emails.to_bcc') !== false){
                    $m->bcc(config('chuckcms-module-order-form.emails.to_bcc'));
                }
            });
        }
    }
}
