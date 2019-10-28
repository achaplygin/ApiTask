<?php

use yii\db\Migration;

/**
 * Class m191027_094739_add_tbl_history
 */
class m191027_094739_add_tbl_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task.history}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('now()'),
            'editor_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'status_id' => $this->integer(),
            'assigned_to' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk__history_task__task_id',
            '{{%task.history}}',
            'task_id',
            '{{%task.task}}',
            'id'
        );
        $this->addForeignKey(
            'fk__history_editor__user_id',
            '{{%task.history}}',
            'editor_id',
            'user',
            'id'
        );
        $this->addForeignKey(
            'fk__history_assigned__user_id',
            '{{%task.history}}',
            'assigned_to',
            'user',
            'id'
        );
        $this->addForeignKey(
            'fk__history_status__status_id',
            '{{%task.history}}',
            'status_id',
            '{{%task.status}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task.history}}');
    }
}
