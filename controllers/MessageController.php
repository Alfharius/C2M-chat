<?php


namespace app\controllers;


use app\components\Functions;
use app\models\Message;
use app\models\Response;
use app\models\Room;
use app\models\User;
use yii\rest\ActiveController;

class MessageController extends ActiveController
{
    public $modelClass = 'app\models\Message';

    public function actionSend(){
        $request = $this->request->bodyParams;

        $message = new $this->modelClass;
        $response = new Response();

        foreach ($request as $field => $val){
            $message[strval($field)] = $val;
        }

        $message->type = 'user';

        $models = Functions::findInDb(['User'=>$request['user_id'], 'Room'=>$request['room_id']]);

        if(Functions::isNull($models)){
            $response->setParams(['code' => 400, 'message' => 'Invalid data', 'data' => 'Data']);
            $response->send($this->response);
            return;
        }

        if ($message->save()) {
            $response->setParams(['code' => 200, 'message' => 'Message sent', 'data' => $message]);
        } else {
            $response->setParams((['code' => 400, 'message' => 'Send error', 'data' => $message->errors]));
        }
        $response->send($this->response);
    }
}