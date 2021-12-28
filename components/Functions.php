<?php


namespace app\components;


use yii\db\Query;

class Functions
{
    public static function checkExist($array){
        foreach ($array as $field => $val){
            unset($array[$field]);
            $field = substr_replace($field, '', -3 );
            $query = (new Query())->from('chat_'.$field.'s')->where(['id'=>$val])->one();
            if ($query)
                $array[] = true;
        }
        return $array;
    }

    public static function searchInArray($array, $sVal, $isAssoc = false){
        $mas = [];
        foreach ($array as $key => $val){

            if ($isAssoc){
                $array[$key] = $val;
                foreach ($array[$key] as $aKey => $aVal){
                    if ($aKey == $sVal){
                        $mas[] = ''.$aVal;
                    }
                }
            } else {
                if ($key == $sVal){
                    $mas[] = ''.$val;
                }
            }

        }
        return $mas;
    }

    public static function isNull($array){
        foreach ($array as $val){
            if ($val == null)
                return true;
        }
        return false;
    }

    public static function findInDb($array){
        $mas = [];
        foreach ($array as $model => $val){
            $model = ucfirst($model);
            $model = "app\models\\".$model;
            $mas[] = $model::findById($val);
        }
        return $mas;
    }
}