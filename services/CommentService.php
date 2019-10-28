<?php


namespace app\services;


use app\models\Comment;
use app\models\CommentForm;
use Yii;
use yii\base\UserException;

class CommentService
{
    private $model;

    /**
     * TaskService constructor.
     * @param Comment $model
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    /**
     * @param CommentForm $form
     * @return bool
     * @throws UserException
     */
    public function create(CommentForm $form)
    {
        $this->model->setAttributes($form->filledAttributes);
        $this->model->author_id = Yii::$app->user->identity->getId();

        if (!$this->model->save()) {
            throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
        }

        return true;
    }

    public function update(CommentForm $form)
    {
        $this->model->setAttributes($form->filledAttributes);
        $this->model->updated_at = Yii::$app->formatter->asDatetime(time());

        if (!$this->model->save()) {
            throw new UserException(implode(PHP_EOL, $this->model->firstErrors));
        }

        return true;
    }
}