<?php

namespace app\models;

use yii\base\Model;

/**
 *
 * @property array $filledAttributes
 */
class CommentForm extends Model
{
    public $text;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['text'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
