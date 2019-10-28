<?php

namespace app\services;

use app\models\History;
use app\models\Task;
use Yii;
use yii\base\UserException;

class HistoryService
{
    public static function writeHistroy(Task $task)
    {
        $history = new History();
        $history->setAttributes([
            'task_id' => $task->id,
            'editor_id' => Yii::$app->user->identity->getId(),
            'description' => $task->description,
            'status_id' => $task->status_id,
            'assigned_to' => $task->assigned_to,
        ]);
        if (!$history->save()) {
            throw new UserException(implode(PHP_EOL, $history->firstErrors));
        }

        return true;
    }
}
