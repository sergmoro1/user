<?php
namespace sergmoro1\user\components\convertor;

class Facebook implements Convertor {
    public function set($obj, $attributes)
    {
		$obj->name = $attributes['name'];
		$obj->email = isset($attributes['email']) ? $attributes['email'] : false;
    }
}
