<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RewardRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the rewards.
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-order-form.rewards.slug'))->get();
    }

    /**
     * Get the reward with the given id.
     *
     * @var string
     **/
    public function byId($id)
    {
        return $this->repeater
                    ->where('slug', config('chuckcms-module-order-form.rewards.slug'))
                    ->where('id', $id)
                    ->first();
    }

    public function save(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-order-form.rewards.slug');
        $input['url'] = config('chuckcms-module-order-form.rewards.url').str_slug($values->get('name'), '-');
        $input['page'] = config('chuckcms-module-order-form.rewards.page');

    	$json = [];
        $json['slug'] = Str::slug($values->get('name'), '-');
        $json['name'] = $values->get('name');
        $json['points'] = (int)$values->get('points');
        $json['image'] = $values->get('image');
        $json['discount'] = $values->get('discount');

        $input['json'] = $json;

        $of_reward = $this->repeater->create($input);

        return $of_reward;
    }

    public function update(Request $values)
    {
        $of_reward = $this->repeater->where('id', $values->get('id'))->firstOrFail();
        $of_reward->url = config('chuckcms-module-order-form.rewards.url').str_slug($values->get('name'), '-');

        $json = [];
        $json['slug'] = Str::slug($values->get('name'), '-');
        $json['name'] = $values->get('name');
        $json['points'] = (int)$values->get('points');
        $json['image'] = $values->get('image');
        $json['discount'] = $values->get('discount');

        $of_reward->json = $json;

        $of_reward->update();

        return $of_reward;
    }

    public function delete(int $id)
    {
    	$of_reward = $this->byId($id);
        if (is_null($of_reward)) {
            return 'false';
        }
        
        if ($of_reward->delete()) {
            return 'success';
        }

        return 'error';    
    }

}