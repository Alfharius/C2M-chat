<?php


namespace app\controllers;


use app\models\Response;
use app\components\Functions;
use app\models\Room;
use app\models\User;
use yii\rest\ActiveController;

class RoomController extends ActiveController
{
    public $modelClass = 'app\models\Room';

    public function actionAdd()
    {
        $requestBody = $this->request->bodyParams;
        $response = new Response();

        $room = Room::findOne(['id' => $requestBody['room_id']]);
        $user = User::findOne(['id' => $requestBody['user_id']]);

        if(Functions::isNull([$room, $user])){
            $response->setParams(['code' => $this->response->statusCode, 'message' => 'Invalid data', 'data' => 'Not found models']);
            $response->send($this->response);
            return;
        }

        $users = Functions::searchInArray($room->users, 'id', true);
        if (in_array($user['id'], $users)) {
            $response->setParams(['code' => 400, 'message' => 'User already in room', 'data' => $user]);
        } else {
            $room->link('users', $user);
            if ($room->save()) {
                $response->setParams(['code' => 200, 'message' => 'User added to room', 'data' => $room->users]);
            } else {
                $response->setParams(['code' => 400, 'message' => 'User creation error', 'data' => $room->errors]);
            }
        }

        $response->send($this->response);
    }
}