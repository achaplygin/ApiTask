<?php

use yii\db\Migration;

/**
 * Class m191027_094142_add_tbl_comment
 */
class m191027_094142_add_tbl_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task.comment}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'text' => $this->string()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('now()'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('now()'),
        ]);
        $this->addForeignKey(
            'fk__comment_task__task_id',
            '{{%task.comment}}',
            'task_id',
            '{{%task.task}}',
            'id'
        );
        $this->addForeignKey(
            'fk__comment_author__user_id',
            '{{%task.comment}}',
            'author_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task.comment}}');
    }
}
