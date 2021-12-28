<?php


namespace app\controllers;


use app\models\Response;
use app\models\User;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionCreate(){
        $user = new User();
        $user->status = 'active';

        $user->display_name = $this->request->bodyParams['display_name'];

        $response = new Response();
        if ($user->save()) {
            $response->setParams(['code' => 200, 'message' => 'User created', 'data' => $user]);
        } else {
            $response->setParams(['code' => 400, 'message' => 'User creation error', 'data' => $user->errors]);
        }
        $response->send($this->response);
    }


}