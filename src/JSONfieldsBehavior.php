<?php
/**
 * JSON fields behavior
 *
 * @link https://github.com/inblank/yii2-jsonfields
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\jsonfields;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class JSONfieldsBehavior
 */
class JSONfieldsBehavior extends Behavior
{
    /**
     * Attributes defines
     * [
     *  'attribute-1-name',
     *  'attribute-2-name => [ params ]
     * ]
     * @var array
     */
    public $attributes;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encode',
            ActiveRecord::EVENT_AFTER_INSERT => 'decode',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decode',
            ActiveRecord::EVENT_AFTER_FIND => 'decode',
            ActiveRecord::EVENT_AFTER_REFRESH => 'decode',
        ];
    }

    /**
     * Encode attributes to JSON strings
     */
    public function encode()
    {
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $name => $params) {
                if (is_numeric($name)) {
                    $name = $params;
                }
                /** @var ActiveRecord $owner */
                $owner = $this->owner;
                if ($owner->hasAttribute($name)) {
                    $owner->setAttribute($name, Json::encode($owner->getAttribute($name)));
                }
            }
        }
    }

    /**
     * Decode attributes from JSON string
     */
    public function decode()
    {
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $name => $params) {
                if (is_numeric($name)) {
                    $name = $params;
                }
                /** @var ActiveRecord $owner */
                $owner = $this->owner;
                if ($owner->hasAttribute($name)) {
                    $owner->setAttribute($name, Json::decode($owner->getAttribute($name)));
                }
            }
        }
    }

}
