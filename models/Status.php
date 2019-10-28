<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task.status".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property bool $is_active
 */
class Status extends \yii\db\ActiveRecord
{
    public const
        NEW = 10,
        IN_PROGRESS = 20,
        RESOLVED = 30,
        RETURNED = 40,
        CLOSED = 50,
        DELETED = 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task.status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['is_active'], 'boolean'],
            [['title'], 'string', 'max' => 255],
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
            'is_active' => 'Is Active',
        ];
    }
}
