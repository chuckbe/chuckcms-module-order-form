<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Exports;

use Chuckbe\Chuckcms\Models\FormEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Address',
            'Location Name',
            'Email',
            'Phone',
            'Notifications',
            'NO_Boxes'
        ];
    }

    public function map($order): array
    {
        $date = strpos($order->entry['order_date'], '/') !== false ? 
                    explode('/', $order->entry['order_date'])[1].'/'.explode('/', $order->entry['order_date'])[0].'/'.explode('/', $order->entry['order_date'])[2] :
                    explode('-', $order->entry['order_date'])[1].'/'.explode('-', $order->entry['order_date'])[2].'/'.explode('-', $order->entry['order_date'])[0];

        return [
            $order->entry['order_number'], //order id
            $date, 
            $order->entry['street'] . ' ' . 
            $order->entry['housenumber']  . ', ' . 
            $order->entry['postalcode']  . ' ' . 
            $order->entry['city'], //address
            $order->entry['first_name'] . ' ' . 
            $order->entry['last_name'], //location name
            $order->entry['email'],
            $order->entry['tel'],
            'on',
            '1'
        ];
    }
}
