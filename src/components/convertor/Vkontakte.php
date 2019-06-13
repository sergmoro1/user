<?php
namespace sergmoro1\user\components\convertor;

class Vkontakte implements Convertor {
    public function set($obj, $attributes)
    {
        $obj->username = $attributes['nickname'];
        $obj->email = isset($attributes['email']) ? $attributes['email'] : false;
        $obj->avatar = isset($attributes['photo']) ? $attributes['photo'] : '';
    }
}
