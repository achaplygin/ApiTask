<?php

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\Status;
use app\models\TaskForm;
use app\services\CommentService;
use app\services\HistoryService;
use app\services\TaskService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use app\models\Task;
use app\models\TaskSearch;
use yii\base\UserException;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
            'index' => ['GET'],
            'view' => ['GET'],
            'history' => ['GET'],
            'comments' => ['GET'],
            'create' => ['POST'],
            'add-comment' => ['POST'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
            'assign' => ['PUT'],
            'status' => ['PUT'],
        ];
    }

    /**
     * @return TaskSearch[]|array
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $dataProvider->query->all();
    }

    /**
     * @param $id
     * @return Task
     * @throws UserException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * @return Task|array
     * @throws UserException
     */
    public function actionCreate()
    {
        $model = new Task();
        $service = new TaskService($model);
        $form = new TaskForm();
        $form->setAttributes(Yii::$app->request->post());

        if ($form->validate() && !empty($form->filledAttributes)) {
            $service->create($form);

            return $model;
        }

        return [
            'message' => 'Empty request',
            'You can use this attributes' => [
                'required' => [
                    'title',
                ],
                'optional' => [
                    'description',
                    'status_id',
                    'assigned_to',
                ],
            ]
        ];
    }

    /**
     * @param $id
     * @return Task|array
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $service = new TaskService($model);
        $form = new TaskForm();
        $form->setAttributes(Yii::$app->request->bodyParams);

        if ($form->validate() && !empty($form->filledAttributes)) {
            $service->update($form);

            return $model;
        }

        return [
            'message' => 'Empty request',
            'You can update this attributes' => [
                'title',
                'description',
                'status_id',
                'assigned_to',
            ]
        ];
    }

    /**
     * @param $id
     * @return Task|array|\yii\db\ActiveRecord|null
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        $service = new TaskService($model);
        $assignee = Yii::$app->request->getBodyParam('assigned_to');

        if ($assignee && is_int($assignee)) {
            $service->assign($assignee);

            return $model;
        }

        return [
            'message' => 'Empty request',
            'Required' => 'assigned_to',
        ];
    }

    /**
     * @param $id
     * @return Task|array|\yii\db\ActiveRecord|null
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $service = new TaskService($model);

        if ($status = Yii::$app->request->getBodyParam('status')) {
            $service->setStatus($status);

            return $model;
        }

        return [
            'message' => 'Empty request',
            'Required' => 'status',
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws UserException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $service = new TaskService($model);
        $service->delete();

        return [
            'message' => 'Task was deleted',
        ];
    }

    /**
     * @param $id
     * @return \yii\db\ActiveQuery
     * @throws UserException
     */
    public function actionHistory($id)
    {
        return $this->findModel($id)->history;
    }

    /**
     * @param $id
     * @return \yii\db\ActiveQuery
     * @throws UserException
     */
    public function actionComments($id)
    {
        return $this->findModel($id)->comments;
    }

    /**
     * @param $id
     * @return Comment|array
     * @throws UserException
     */
    public function actionAddComment($id)
    {
        $comment = new Comment(['task_id' => $id]);
        $service = new CommentService($comment);
        $form = new CommentForm();
        $form->setAttributes(Yii::$app->request->post());

        if ($form->validate() && !empty($form->filledAttributes)) {
            $service->create($form);

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
     * @return Task|array|\yii\db\ActiveRecord|null
     * @throws UserException
     */
    protected function findModel($id)
    {
        $model = Task::find()
            ->andWhere(['id' => $id])
            ->andWhere(['not', ['status_id' => Status::DELETED]])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new UserException('The requested task does not exist.', 404);
    }
}
