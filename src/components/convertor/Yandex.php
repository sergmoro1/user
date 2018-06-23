<?php
namespace sergmoro1\user\components\convertor;

class Yandex implements Convertor {
    public function set($obj, $attributes)
    {
        $obj->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
        $obj->email = isset($attributes['default_email']) ? $attributes['default_email'] : false;
        $obj->avatar = isset($attributes['default_avatar_id'])
            ? "https://avatars.yandex.net/get-yapic/{$attributes['default_avatar_id']}/islands-retina-50"
            : '';
    }
}
