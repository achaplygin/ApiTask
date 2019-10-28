<?php

namespace app\controllers;

use app\models\LoginForm;
use app\services\AuthService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'info' => ['GET'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
                'info',
            ],
        ];

        return $behaviors;
    }

    /**
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\UserException
     */
    public function actionLogin()
    {
        $form = new LoginForm(Yii::$app->request->post());
        $service = new AuthService();
        if ($form->validate() && $service->identUser($form)) {
            return $this->asJson([
                'token' => (string)$service->getToken(),
            ]);
        }

        throw new BadRequestHttpException('Incorrect credentials');
    }

    public function actionInfo()
    {
        return $this->asJson([
            'Available commands' => [
                'common' => [
                    'GET /' => 'this list',
                    'POST /login' => 'Authentification',
                ],
                'Users' => [
                    'GET /user' => 'Users list',
                    'POST /user' => 'Create user',
                    'GET /user/<id>' => 'View user',
                    'PUT /user/<id>' => 'Edit user',
                    'DELETE /user/<id>' => 'Delete user (mark as deleted)',
                ],
                'Tasks' => [
                    'GET /task' => 'Tasks list (available filter by query params)',
                    'POST /task' => 'Create task',
                    'GET /task/<id>' => 'View task',
                    'PUT /task/<id>' => 'Edit task',
                    'PUT /task/<id>/assign' => 'Edit task assignment',
                    'PUT /task/<id>/status' => 'Edit task status',
                    'DELETE /task/<id>' => 'Delete task (set status `deleted`)',
                    'GET /task/<id>/comments' => 'View task\'s comments',
                    'POST /task/<id>/comments' => 'Add comment to this task',
                    'GET /task/<id>/history' => 'View task\'s history',
                ],
                'Comments' => [
                    'GET /comment/<id>' => 'View comment',
                    'PUT /comment/<id>' => 'Edit comment',
                    'DELETE /comment/<id>' => 'Delete comment',
                ],
            ]
        ]);
    }
}
