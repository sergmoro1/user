<?php
namespace sergmoro1\user\components\convertor;

/**
 * Social network sends back attributes by request.
 * Converter should extract needed values and set them to internal class variables.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 */
interface Convertor
{
    /**
     * Set object's attributes to the corresponding values from array $attributes.
     * 
     * @param components\SocialContact $obg
     * @param array $attributes
     */
    public function set($obj, $attribute);
}
