<?php

use yii\db\Migration;

/**
 * Class m191027_223516_insert_default_statuses
 */
class m191027_223516_insert_default_statuses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%task.status}}', ['id', 'title', 'description', 'is_active'], [
            ['10', 'New', 'New task', true],
            ['20', 'In progress', 'Executor is working with the task', true],
            ['30', 'Resolved', 'Executor finished work about issue', true],
            ['40', 'Returned', 'The task needs some fixes ', true],
            ['50', 'Closed', 'Issue resolve was accepted', false],
            ['60', 'Deleted', 'The task is deleted', false],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('TRUNCATE {{%task.status}} CASCADE;');
    }
}
