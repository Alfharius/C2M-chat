<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @property int $id
 * @property string $display_name
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';

    public static function tableName(): string
    {
        return 'chat_users';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['display_name', 'status'], 'string'],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE]],
            [['display_name', 'status'], 'required'],
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getRooms(): ActiveQuery
    {
        return $this->hasMany(Room::class, ['id' => 'room_id'])
            ->viaTable('chat_room_users', ['user_id' => 'id']);
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['user_id' => 'id']);
    }
}