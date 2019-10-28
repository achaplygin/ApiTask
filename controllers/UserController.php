<?php

namespace app\controllers;

use app\models\User;
use app\models\UserForm;
use app\services\UserService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\UserException;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\rest\ViewAction;
use yii\rest\IndexAction;

class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => User::class,
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => User::class,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            'identity' => ['GET', 'HEAD'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * @return \app\models\User|null
     * @throws UserException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $form = new UserForm(Yii::$app->request->post());
        $service = new UserService();
        if ($form->validate()) {
            return $service->create($form);
        }

        throw new UserException(implode(PHP_EOL, $form->firstErrors));
    }

    /**
     * @param int $id
     * @return User|null
     * @throws UserException
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    public function actionUpdate(int $id)
    {
        $form = new UserForm();
        $form->setAttributes(Yii::$app->request->bodyParams);
        $service = new UserService();
        if ($form->validate()) {
            return $service->update($id, $form);
        }

        throw new UserException(implode(PHP_EOL, $form->firstErrors));
    }

    /**
     * @return \yii\web\IdentityInterface|null
     */
    public function actionIdentity()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     * @throws UserException
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $service = new UserService();

        return $service->delete($user);
    }

    /**
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function findModel(int $id): User
    {
        if ($user = User::findIdentity($id)) {
            return User::findIdentity($id);
        }

        throw new NotFoundHttpException('User not found');
    }
}
