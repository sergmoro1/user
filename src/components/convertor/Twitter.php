<?php
namespace sergmoro1\user\components\convertor;

class Twitter implements Convertor {
    public function set($obj, $attributes)
    {
		$obj->name = $attributes['name'];
		// twitter shows email only the first time
		$obj->email = isset($attributes['email']) ? $attributes['email'] : false;
    }
}
