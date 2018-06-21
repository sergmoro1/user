<?php
namespace sergmoro1\user\components\convertor;

class Github implements Convertor {
    public function set($obj, $attributes)
    {
		$obj->name = $attributes['name'];
		$obj->email = isset($attributes['email']) ? $attributes['email'] : false;
		$obj->avatar = isset($attributes['owner']['avatar_url']) ? $attributes['owner']['avatar_url'] : false;
    }
}
