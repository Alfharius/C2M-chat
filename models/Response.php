<?php


namespace app\models;


use phpDocumentor\Reflection\DocBlock\Tags\Property;

/**
 * @property int $code
 * @property string $message
 * @property string $data
 */

class Response
{
    public function setParams($paramsArr){
        foreach ($paramsArr as $key => $param){
            $this->$key = $param;
        }
    }

    public function send($response){
        $response->statusCode = $this->code;
        $response->statusText = $this->message;
        $response->data = $this->data;
    }
}