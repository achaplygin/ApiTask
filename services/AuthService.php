<?php

namespace app\services;

use app\models\LoginForm;
use app\models\User;
use Lcobucci\JWT\Token;
use sizeg\jwt\Jwt;
use Yii;
use yii\base\UserException;

/**
 * Class AuthService
 *
 * @property User $user
 */
class AuthService
{
    /** @var User */
    private $user;

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        /** @var Jwt $jwt */
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        // Use lcobucci/jwt ^4.0 version
        $token = $jwt->getBuilder()
            ->identifiedBy('4f1g23a12aa', true)
            ->issuedAt($time)
            ->expiresAt($time + 3600)
            ->withClaim('uid', $this->user->id)
            ->getToken($signer, $key);

        return $token;
    }

    /**
     * @param LoginForm $form
     * @return null
     * @throws UserException
     * @throws \yii\base\Exception
     */
    public function identUser(LoginForm $form): ?User
    {
        if ($this->getUser($form->login) && $this->validatePassword($form->password)) {
            return $this->user;
        }

        throw new UserException('Incorrect login or password', 403);
    }

    /**
     * @param $rawPassword
     * @return bool
     * @throws UserException
     * @throws \yii\base\Exception
     */
    public function validatePassword($rawPassword): bool
    {
        return (Yii::$app->getSecurity()->validatePassword($rawPassword, $this->user->password));
    }

    /**
     * @param $login
     * @return User|null
     * @throws UserException
     */
    public function getUser($login): ?User
    {
        $this->user = User::findByUsername($login);

        return $this->user;
    }
}
