<?php

use yii\db\Migration;

/**
 * Class m191027_224642_insert_default_data
 */
class m191027_224642_insert_default_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%task.task}}',
            ['id', 'title', 'description', 'status_id', 'assigned_to', 'author_id', 'created_at', 'updated_at'],
            [
                [10, 'Test task', 'Some very interesting text.', 30, 1, 2, '2019-10-27 22:48:57', '2019-10-28 06:42:30'],
                [11, 'Another issue', 'Nobody reads descriptions.', 20, 3, 1, '2019-10-28 05:44:33', '2019-10-28 08:21:37'],
            ]
        );
        $this->batchInsert(
            '{{%task.history}}',
            ['task_id', 'created_at', 'editor_id', 'description', 'status_id', 'assigned_to'],
            [
                [10, '2019-10-27 22:48:57', 2, 'Some very interesting text. ', 10, 3],
                [10, '2019-10-28 04:53:11', 3, 'Not very interesting text. ', 20, 3],
                [11, '2019-10-28 05:44:33', 1, 'Nobody reads descriptions. ', 10, 1],
                [10, '2019-10-28 06:42:30', 1, 'Not very interesting text. ', 30, 1],
                [11, '2019-10-28 08:21:37', 1, 'Nobody reads descriptions. ', 20, 3],
            ]
        );
        $this->batchInsert(
            '{{%task.comment}}',
            ['task_id', 'author_id', 'text', 'created_at', 'updated_at'],
            [
                [10, 3, 'Wrong description.', '2019-10-28 04:49:01', '2019-10-28 04:49:01'],
                [10, 1, 'No one cares..', '2019-10-28 05:30:22', '2019-10-28 05:30:22'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%task.history}}');
        $this->truncateTable('{{%task.comment}}');
        $this->execute('TRUNCATE {{%task.task}} CASCADE;');
    }
}
