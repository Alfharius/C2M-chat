<?php


namespace app\controllers;


use app\components\Functions;
use app\models\LastRead;
use app\models\Message;
use app\models\Response;
use app\models\Room;
use yii\rest\ActiveController;

class LastReadController extends ActiveController
{
    public $modelClass = 'app\models\LastRead';

    public function actionRead()
    {
        $readMessage = new LastRead();
        $response = new Response();
        $requestBody = $this->request->bodyParams;

        foreach ($requestBody as $field => $val) {
            $readMessage[strval($field)] = intval($val);
        }

        $array = Functions::checkExist($requestBody);

        $existedMessage = LastRead::findOne(['user_id' => $requestBody['user_id'], 'room_id' => $requestBody['room_id']]);

        if (Message::findById($readMessage->message_id)->room_id != $readMessage->room_id){
            $response->setParams(['code'=>400,'message'=>'Invalid room','data'=>$readMessage->room_id]);
            $response->send($this->response);
            return;
        }

        if (count($array) == 3) {
            if (!is_null($existedMessage)) {
                $existedMessage->message_id = $readMessage->message_id;
                $existedMessage->save();
                $response->setParams([['code' => 200, 'message' => 'Last read updated', 'data' => $existedMessage]]);
            }
            else {
                if ($readMessage->save())
                    $response->setParams(['code' => 200, 'message' => 'Message read', 'data' => $readMessage]);
                else
                    $response->setParams(['code' => 400, 'message' => 'Read error', 'data' => $readMessage->errors]);
            }
        }
        else
            $response->setParams(['code' => 400, 'message' => 'Invalid data', 'data' => 'Data']);

        $response->send($this->response);
    }
}