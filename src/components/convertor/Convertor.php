<?php
namespace sergmoro1\user\components\convertor;

use sergmoro1\user\componets\SocialContact;

interface Convertor
{
    public function set(SocialContact $obj, $attribute);
}
