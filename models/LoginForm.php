<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Class LoginForm
 * @package app\models
 *
 * @property string $login
 * @property string $password
 */
class LoginForm extends Model
{
    public $login;
    public $password;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password'], 'string', 'max' => 255],
            [['login', 'password'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['login'], 'filter', 'filter' => function ($value) {
                return strtolower($value);
            }],
        ];
    }
}
