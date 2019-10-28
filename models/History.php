<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task.history".
 *
 * @property int $id
 * @property int $task_id
 * @property string $created_at
 * @property int $editor_id
 * @property string $description
 * @property int $status_id
 * @property int $assigned_to
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task.history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'editor_id', 'status_id', 'assigned_to'], 'default', 'value' => null],
            [['task_id', 'editor_id', 'status_id', 'assigned_to'], 'integer'],
            [['created_at'], 'safe'],
            [['editor_id'], 'required'],
            [['description'], 'string'],
            [['editor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['editor_id' => 'id']],
            [['assigned_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assigned_to' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'created_at' => 'Created At',
            'editor_id' => 'Editor ID',
            'description' => 'Description',
            'status_id' => 'Status ID',
            'assigned_to' => 'Assigned To',
        ];
    }
}
