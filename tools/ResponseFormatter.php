<?php

namespace dee\angular\tools;

use Yii;

/**
 * ResponseFormatter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ResponseFormatter extends \yii\web\JsonResponseFormatter
{
    /**
     * @var string 
     */
    public $serializer = 'dee\angular\tools\Serializer';

    /**
     * @inheritdoc
     */
    public function format($response)
    {
        $response->data = $this->serializeData($response->data);
        parent::format($response);
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     * @param mixed $data the data to be serialized
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }
}