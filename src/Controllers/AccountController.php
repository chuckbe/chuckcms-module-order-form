<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\Chuckcms\Models\Template;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CouponRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\RewardRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AccountController extends Controller
{
    private $couponRepository;

    private $customerRepository;

    private $rewardRepository;

    protected $templateHintpath;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CouponRepository $couponRepository, CustomerRepository $customerRepository, RewardRepository $rewardRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->customerRepository = $customerRepository;
        $this->rewardRepository = $rewardRepository;
        $this->templateHintpath = config('chuckcms-module-order-form.auth.template.hintpath');

        $this->middleware('role:customer');
    }

    public function index()
    {
        $customer = $this->customerRepository->findByUserId(Auth::user()->id);
        $template = Template::where('active', 1)->where('hintpath', $this->templateHintpath)->first();
        $blade = $this->templateHintpath.'::templates.'.$this->templateHintpath.'.'.config('chuckcms-module-order-form.account.template.account');
        
        if (view()->exists($blade)) {
            return view($blade, compact('template', 'customer'));
        }

        return abort(404);
    }

    public function swapPoints()
    {
        $customer = $this->customerRepository->findByUserId(Auth::user()->id);
        $rewards = $this->rewardRepository->get();
        $template = Template::where('active', 1)->where('hintpath', $this->templateHintpath)->first();
        $blade = $this->templateHintpath.'::templates.'.$this->templateHintpath.'.'.config('chuckcms-module-order-form.account.template.swap_points');
        
        if (view()->exists($blade)) {
            return view($blade, compact('template', 'customer', 'rewards'));
        }

        return abort(404);
    }

    public function coupons()
    {
        $customer = $this->customerRepository->findByUserId(Auth::user()->id);
        $coupons = $this->couponRepository->forCustomer($customer);
        $template = Template::where('active', 1)->where('hintpath', $this->templateHintpath)->first();
        $blade = $this->templateHintpath.'::templates.'.$this->templateHintpath.'.'.config('chuckcms-module-order-form.account.template.coupons');
        
        if (view()->exists($blade)) {
            return view($blade, compact('template', 'customer', 'coupons'));
        }

        return abort(404);
    }
}
