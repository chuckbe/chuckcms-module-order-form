<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
        ];
    }
}
