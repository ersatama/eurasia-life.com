<?php

use yii\db\Migration;
use console\migrations\MigrationHelpersTrait;

/**
 * Class m190131_090038_create_backup_table — Создаем таблицу для бэкапов
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class m190131_090038_create_backup_table extends Migration
{
    use MigrationHelpersTrait;

    /**
     * @var string – таблица бэкапов
     */
    protected $backupTable = 'backup';

    /**
     * @var string – таблица пользователей
     */
    protected $userTable = 'user';

    /**
     * {@inheritdoc}
     * @return bool|void
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $tableRaw = $this->backupTable;
        $table = $this->table($tableRaw);

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'data' => $this->longText(),
            'old_data' => $this->longText(),
            'action' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey("{{%fk-$tableRaw-created_by}}", $table, 'created_by', $this->userTable, 'id');

        $this->createIndex("{{%idx-$tableRaw-key}}", $table, 'key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableRaw = $this->backupTable;
        $table = $this->table($tableRaw);

        $this->dropForeignKey("{{%fk-$tableRaw-created_by}}", $table);
        $this->dropTable($table);
    }
}
