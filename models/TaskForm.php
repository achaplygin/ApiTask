<?php

namespace app\models;

use yii\base\Model;

/**
 *
 * @property array $filledAttributes
 */
class TaskForm extends Model
{
    public $title;
    public $description;
    public $status_id;
    public $assigned_to;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string', 'max' => 255],
            [['title', 'description'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['status_id', 'assigned_to'], 'integer'],
            [['status_id'], 'exist', 'targetClass' => Status::class, 'targetAttribute' => 'id'],
            [['assigned_to'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return array
     */
    public function getFilledAttributes(): array
    {
        return array_filter($this->attributes, function ($value) {
            return !empty($value);
        });
    }
}
