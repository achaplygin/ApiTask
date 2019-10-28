<?php

namespace app\services;

use app\models\Status;
use app\models\Task;
use app\models\TaskForm;
use app\models\User;
use Yii;
use yii\base\UserException;

class TaskService
{
    /**
     * @var Task
     */
    private $model;

    /**
     * TaskService constructor.
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * @param TaskForm $form
     * @return bool
     * @throws UserException
     */
    public function create(TaskForm $form)
    {
        $this->model->setAttributes($form->filledAttributes);
        $this->model->author_id = Yii::$app->user->identity->getId();
        $this->model->status_id = $form->status_id ?? Status::NEW;

        if (!$this->model->save()) {
            throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
        }
        HistoryService::writeHistroy($this->model);

        return true;
    }

    /**
     * @param TaskForm $form
     * @return bool
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function update(TaskForm $form)
    {
        $this->model->setAttributes($form->filledAttributes);
        $this->model->updated_at = Yii::$app->formatter->asDatetime(time());

        if (!$this->model->save()) {
            throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
        }
        HistoryService::writeHistroy($this->model);

        return true;
    }

    /**
     * @param $user_id
     * @return bool
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function assign($user_id)
    {
        if (User::findOne($user_id)) {
            $this->model->assigned_to = $user_id;
            $this->model->updated_at = Yii::$app->formatter->asDatetime(time());

            if (!$this->model->save()) {
                throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
            }

            HistoryService::writeHistroy($this->model);
        } else {
            throw new UserException('Requested user not found', 404);
        }

        return true;
    }

    /**
     * @param $requestedStatus
     * @return bool
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     */
    public function setStatus($requestedStatus)
    {
        $status =
            Status::findOne((int)$requestedStatus)
            ?? Status::find()->andWhere([
                'or',
                ['ilike', 'title', $requestedStatus],
                ['ilike', 'description', $requestedStatus],
            ])->one();

        if ($status) {
            $this->model->status_id = $status->id;
            $this->model->updated_at = Yii::$app->formatter->asDatetime(time());

            if (!$this->model->save()) {
                throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
            }

            HistoryService::writeHistroy($this->model);
        } else {
            throw new UserException('Requested status not found', 404);
        }

        return true;
    }

    /**
     * @return bool
     * @throws UserException
     */
    public function delete()
    {
        $this->model->status_id = Status::DELETED;
        HistoryService::writeHistroy($this->model);

        if (!$this->model->save()) {
            throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
        }

        return true;
    }
}
