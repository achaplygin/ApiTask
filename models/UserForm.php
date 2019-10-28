<?php

namespace app\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class UserForm extends Model
{
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'string', 'min' => 3, 'max' => 255, 'skipOnEmpty' => true],
            [['username', 'password'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['username'], 'filter', 'filter' => function ($value) {
                return strtolower($value);
            }],
        ];
    }
}
