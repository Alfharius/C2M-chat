<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $settings
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Room extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';

    public static function tableName(): string
    {
        return 'chat_rooms';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'string'],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE]],
            [['status'], 'required'],
        ];
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['room_id' => 'id']);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('chat_room_users', ['room_id' => 'id']);
    }

}