<?php
namespace sergmoro1\user\components\convertor;

class Google implements Convertor {
    public function set($obj, $attributes)
    {
        $obj->name = $attributes['name']['givenName'] . ' ' . $attributes['name']['familyName'];
        $obj->email = isset($attributes['emails'][0]['value']) ? $attributes['emails'][0]['value'] : false;
        $obj->avatar = isset($attributes['image']['url']) ? $attributes['image']['url'] : '';
    }
}
