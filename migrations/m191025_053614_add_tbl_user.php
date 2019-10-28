<?php

use yii\db\Migration;

/**
 * Class m191025_053614_add_tbl_user
 */
class m191025_053614_add_tbl_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->unique()->notNull(),
            'password' => $this->string()->notNull(),
            'deleted' => $this->boolean()->defaultValue(false)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('now()'),
            'updated_at' => $this->dateTime()->defaultExpression('now()'),
        ]);

        $this->batchInsert('user', ['username', 'password'], [
            [
                'username' => 'admin',
                'password' => Yii::$app->getSecurity()->generatePasswordHash('password'),
            ],
            [
                'username' => 'user',
                'password' => Yii::$app->getSecurity()->generatePasswordHash('user'),
            ],
            [
                'username' => 'demo',
                'password' => Yii::$app->getSecurity()->generatePasswordHash('demo'),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
