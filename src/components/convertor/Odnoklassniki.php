<?php
namespace sergmoro1\user\components\convertor;

class Odnoklassniki implements Convertor {
    public function set($obj, $attributes)
    {
        $obj->username = $attributes['first_name'] . ' ' . $attributes['last_name'];
        $obj->email = isset($attributes['email']) ? $attributes['email'] : false;
        $obj->avatar = isset($attributes['pic_2']) ? $attributes['pic_2'] : '';
    }
}
