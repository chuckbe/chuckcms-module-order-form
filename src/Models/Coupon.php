<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Models;

use ChuckRepeater;
use Eloquent;

class Coupon extends Eloquent
{
    const STATUS_AWAITING = 'awaiting';
    const STATUS_USED = 'used';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cof_coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'number', 'status', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo('Chuckbe\ChuckcmsModuleOrderForm\Models\Customer');
    }

    public function getRewardAttribute()
    {
    	$reward_slug = $this->json['reward'];
    	return ChuckRepeater::for(config('chuckcms-module-order-form.rewards.slug'))
    									->first(function ($value, $key) use ($reward_slug) {
										    return $value->json['slug'] == $reward_slug;
										});
    }
}
