<?php

use yii\db\Migration;

/**
 * Class m191026_165558_add_tbl_task
 */
class m191026_165558_add_tbl_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE SCHEMA task');
        $this->createTable('{{%task.task}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'status_id' => $this->integer(),
            'assigned_to' => $this->integer(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('now()'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('now()'),
        ]);

        $this->createTable('{{%task.status}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'is_active' => $this->boolean(),
        ]);

        $this->addForeignKey(
            'fk__task_status__status_id',
            '{{%task.task}}',
            'status_id',
            '{{%task.status}}',
            'id'
        );

        $this->addForeignKey(
            'fk__task_author__user_id',
            '{{%task.task}}',
            'author_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk__task_assigned__user_id',
            '{{%task.task}}',
            'assigned_to',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task.task}}');
        $this->dropTable('{{%task.status}}');
        $this->execute('DROP SCHEMA task');
    }
}
