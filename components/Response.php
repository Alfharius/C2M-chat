<?php


namespace app\components;


class Response
{
    public function createResponse($sentCode, $sentMessage, $sentData){
        $this->code = $sentCode;
        $this->message = $sentMessage;
        $this->data = $sentData;
    }

    public function responseSend($response){
        $response->statusCode = $this->code;
        $response->statusText = $this->message;
        $response->data = $this->data;
    }
}