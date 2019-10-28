<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "task.task".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $status_id
 * @property int $assigned_to
 * @property int $author_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ActiveQuery $history
 * @property ActiveQuery $comments
 * @property Status $status
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task.task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id'], 'required'],
            [['description'], 'string'],
            [['status_id', 'assigned_to', 'author_id'], 'default', 'value' => null],
            [['status_id', 'assigned_to', 'author_id'], 'integer'],
            [['status', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assigned_to' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status_id' => 'Status ID',
            'assigned_to' => 'Assigned To',
            'author_id' => 'Author ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHistory()
    {
        return $this->hasMany(History::class, ['task_id' => 'id']);
    }
}
