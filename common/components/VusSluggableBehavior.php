<?php
namespace common\components;

use yii\behaviors\SluggableBehavior;
use common\helpers\VusInflector;

/**
 * 
 */

class VusSluggableBehavior extends SluggableBehavior
{
    /**
     * Override SluggableBehavior/generateSlug
     * This method is called by [[getValue]] to generate the slug.
     * You may override it to customize slug generation.
     * The default implementation calls [[\yii\helpers\Inflector::slug()]] on the input strings
     * concatenated by dashes (`-`).
     * @param array $slugParts an array of strings that should be concatenated and converted to generate the slug value.
     * @return string the conversion result.
     */
    protected function generateSlug($slugParts)
    {
        return VusInflector::slug(implode('-', $slugParts));
    }
}

