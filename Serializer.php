<?php

namespace dee\angular;

/**
 * Description of Serialize
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Serializer extends \yii\rest\Serializer
{

    /**
     * @inheritdoc
     */
    public function serialize($data)
    {
        if (is_array($data)) {
            return $this->serializeModels($data);
        } else {
            return parent::serialize($data);
        }
    }
}