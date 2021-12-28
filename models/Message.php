<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $room_id
 * @property string $type
 * @property string $body
 * @property string $status
 * @property string $mentioned_to
 * @property int    $replied_to
 * @property string $created_at
 * @property string $updated_at
 */
class Message extends \yii\db\ActiveRecord
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER   = 'user';

    const STATUS_ACTIVE = 'active';

    public static function tableName(): string
    {
        return 'chat_messages';
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
            [['user_id', 'room_id', 'replied_to'], 'integer'],
            [['type', 'status', 'body'], 'string'],
            [['type'], 'in', 'range' => [self::TYPE_SYSTEM, self::TYPE_USER]],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE]],
            ['mentioned_to', 'safe'],
            [['type', 'status', 'user_id', 'room_id', 'body'], 'required'],
        ];
    }

    public function getRoom(): ActiveQuery
    {
        return $this->hasOne(Room::class, ['id' => 'room_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}