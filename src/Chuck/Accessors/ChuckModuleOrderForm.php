<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\OrderFormRepository;
use Exception;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class ChuckModuleOrderForm
{
    private $orderFormRepository;
    private $productRepository;

    public function __construct(OrderFormRepository $orderFormRepository, ProductRepository $productRepository) 
    {
        $this->orderFormRepository = $orderFormRepository;
        $this->productRepository = $productRepository;
    }

    public function renderForm()
    {
        return $this->orderFormRepository->render();
    }

    public function renderScripts()
    {
        return $this->orderFormRepository->scripts();
    }

    public function renderStyles()
    {
        return $this->orderFormRepository->styles();
    }

    public function followupContent(string $order_number)
    {
        return $this->orderFormRepository->followup($order_number);
    }

    public function followupScripts(string $order_number)
    {
        return $this->orderFormRepository->followupScripts($order_number);
    }

    public function followupStyles(string $order_number)
    {
        return $this->orderFormRepository->followupStyles($order_number);
    }

    public function firstAvailableDate(string $locationKey)
    {
        return $this->orderFormRepository->firstAvailableDate($locationKey);
    }

    public function firstAvailableDateInDaysFromNow(string $locationKey)
    {
        return $this->orderFormRepository->firstAvailableDateInDaysFromNow($locationKey);
    }

    public function totalSales()
    {
        return $this->orderFormRepository->totalSales();
    }

    public function totalSalesLast7Days()
    {
        return $this->orderFormRepository->totalSalesLast7Days();
    }

    public function totalSalesLast7DaysQty()
    {
        return $this->orderFormRepository->totalSalesLast7DaysQty();
    }

}