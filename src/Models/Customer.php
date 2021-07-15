<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Models;

use Eloquent;

class Customer extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cof_customers';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'surname', 'name', 'email', 'dob', 'tel', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key) ?? $this->getJson($key);
    }

    public function user()
    {
        return $this->belongsTo('Chuckbe\Chuckcms\Models\User');
    }

    public function coupons()
    {
        return $this->hasMany('Chuckbe\ChuckcmsModuleOrderForm\Models\Coupon');
    }

    public function getGuestAttribute() :bool
    {
        return $this->user_id == null;
    }

    public function getBillingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['billing'] : array();
    }

    public function getShippingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['shipping'] : array();
    }

    public function getJson(string $string)
    {
        $json = $this->resolveJson($string);
        return $json ? $json : null;
    }

    private function resolveJson($var)
    {
        $json = $this->json;
        $split = explode('.', $var);
        foreach ($split as $value) {
            if (array_key_exists($value, $json)) {
                $json = $json[$value];
            } else {
                return null;
            }
        }

        return $json;
    }
}
