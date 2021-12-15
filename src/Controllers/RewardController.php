<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\CouponRepository;
use Chuckbe\ChuckcmsModuleOrderForm\Chuck\RewardRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class RewardController extends Controller
{
    private $customerRepository;

    private $couponRepository;

    private $rewardRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository, 
        CouponRepository $couponRepository, 
        RewardRepository $rewardRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->couponRepository = $couponRepository;
        $this->rewardRepository = $rewardRepository;
    }

    public function index()
    {
        $rewards = $this->rewardRepository->get();
        return view('chuckcms-module-order-form::backend.rewards.index', compact('rewards'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'points' => 'numeric|required',
            'image' => 'required',
            'discount' => 'required',
            'id' => 'required_with:update'
        ]);
        
        if ($request->has('create')) {
            $reward = $this->rewardRepository->save($request);
        }

        if ($request->has('id') && $request->has('update')) {
            $reward = $this->rewardRepository->update($request);
        } 

        if (!$reward->save()){
            return 'error';//add ThrowNewException
        }

        return redirect()->route('dashboard.module.order_form.rewards.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->rewardRepository->delete($request->get('id'));

        if ($delete){
            return redirect()->route('dashboard.module.order_form.rewards.index');
        }
    }
    
    public function swap(Request $request)
    {
        $this->validate($request, [
            '_reward_id' => 'required'
        ]);

        $user = Auth::user();
        $customer = $this->customerRepository->findByUserId($user->id);

        $reward = $this->rewardRepository->byId($request->get('_reward_id'));
        if (is_null($reward)) {
            return redirect()->back()->with('no_coupon', true);
        }

        $necessary_points = (int)$reward->points;
        if($necessary_points > (int)$customer->loyalty_points) {
            return redirect()->back()->with('not_enough_points', true);
        }

        $coupon = $this->couponRepository->createFromReward($reward, $customer);
        // $new_coupon = new Coupon;
        // $new_coupon->user_id = $user->id;
        // $new_coupon->number = $this->generateCouponNumber();
        // $new_coupon->status = Coupon::STATUS_AWAITING;
        // $json = [];
        // $json['reward'] = $coupon;
        // $new_coupon->json = $json;
        // $new_coupon->save();

        // $json = $customer->json;
        // $json['loyalty_points'] = (int)$customer->json['loyalty_points'] - $necessary_points;
        // $customer->json = $json;
        // $customer->save();

        return redirect('/mijn-account/coupons')->with('swapped', true);
    }
}
