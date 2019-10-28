<?php

namespace app\controllers;

use app\models\CommentForm;
use app\services\CommentService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use app\models\Comment;
use yii\base\UserException;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
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
     * @inheritDoc
     */
    protected function verbs()
    {
        return [
            'view' => ['GET'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * @param $id
     * @return Comment|null
     * @throws UserException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * @param $id
     * @return Comment|array
     * @throws UserException
     */
    public function actionUpdate($id)
    {
        $comment = $this->findModel($id);
        $service = new CommentService($comment);
        $form = new CommentForm();
        $form->setAttributes(Yii::$app->request->bodyParams);

        if ($form->validate() && !empty($form->filledAttributes)) {
            $service->update($form);

            return $comment;
        }

        return !empty($comment->firstErrors)
            ? $comment->firstErrors
            : [
                'message' => 'Empty request or incorrect parameters',
                'Required attributes' => ['text']
            ];
    }

    /**
     * @param $id
     * @return Response
     * @throws UserException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Comment
     * @throws UserException
     */
    protected function findModel($id): Comment
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new UserException('The requested comment does not exist.', 404);
    }
}
