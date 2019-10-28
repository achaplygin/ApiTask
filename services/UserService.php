<?php

namespace app\services;

use app\models\User;
use app\models\UserForm;
use Yii;
use yii\base\UserException;
use yii\db\Exception;

class UserService
{
    /**
     * @param UserForm $form
     * @return User|null
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function create(UserForm $form): ?User
    {
        $user = new User();
        $user->setAttributes($form->attributes);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($form->password);
        if (!$user->save()) {
            throw new UserException(implode(PHP_EOL, $user->firstErrors));
        }
        $user->refresh();
        return $user;
    }

    /**
     * @param int $id
     * @param UserForm $form
     * @return User|null
     * @throws UserException
     * @throws \yii\base\Exception
     */
    public function update(int $id, UserForm $form): ?User
    {
        if ($user = $this->findModel($id)) {
            foreach ($form->attributes as $key => $value) {
                if (isset($user->$key) && !empty($value)) {
                    $user->$key = $value;
                }
            }
            if ($form->password) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($form->password);
            }
            if (!$user->save()) {
                throw new UserException(implode(PHP_EOL, $user->firstErrors));
            }
        }
        $user->refresh();
        return $user;
    }

    /**
     * @param User $user
     * @return User
     * @throws UserException
     */
    public function delete(User $user)
    {
        $user->deleted = true;
        if (!$user->save()) {
            throw new UserException(implode(PHP_EOL, $user->firstErrors));
        }
        return $user;
    }

    /**
     * @param int $id
     * @return User|null
     */
    private function findModel(int $id): ?User
    {
        return User::findIdentity($id);
    }
}
