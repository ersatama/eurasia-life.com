<?php

use yii\db\Migration;
use console\migrations\MigrationHelpersTrait;

/**
 * Class m180419_100000_init
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class m180419_100000_init extends Migration
{
    use MigrationHelpersTrait;

    /**
     * @var string – таблица пользователей
     */
    protected $userTable = 'user';

    /**
     * @var string – таблица контент-страниц
     */
    protected $pageTable = 'page';

    /**
     * @var string – таблица файлов
     */
    protected $fileTable = 'file';

    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this
            ->upUserTable()
            ->upPageTable()
            ->upFileTable();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this
            ->downFileTable()
            ->downPageTable()
            ->downUserTable();
    }

    /**
     * Поднимаем таблицу пользователей
     * @return $this
     */
    protected function upUserTable()
    {
        return $this->createTableWithCommonColumns($this->userTable, [
            'email' => $this->string()->unique(),
            'password_hash' => $this->string()->append('CHARACTER SET utf8 COLLATE utf8_bin'),
            'name' => $this->string(),
            'identity_at' => $this->integer(),
            'login_at' => $this->integer(),
        ]);
    }

    /**
     * Поднимаем таблицу контент-страниц
     * @return $this
     * @throws \yii\base\NotSupportedException
     */
    protected function upPageTable()
    {
        $tableRaw = $this->pageTable;
        $table = $this->table($tableRaw);

        $this->createTableWithCommonColumns($tableRaw, [
            'slug' => $this->string(),
            'name' => $this->string(),
            'title' => $this->string(),
            'body' => $this->mediumText(),

            'views' => $this->integer(),
            'visible' => $this->boolean(),

            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
        ]);

        $this->createIndex("{{%idx-$tableRaw-slug}}", $table, 'slug');

        return $this;
    }

    /**
     * Поднимаем таблицу файлов
     * @return $this
     */
    protected function upFileTable()
    {
        $tableRow = $this->fileTable;
        $table = $this->table($tableRow);

        $this->createTable($tableRow, [
            'id' => $this->primaryKey(),
            'filename' => $this->string(),
            'size' => $this->integer(),
            'mime_type' => $this->string(),
            'original_filename' => $this->string(),
            'owner_class_id' => $this->smallInteger(),
            'owner_instance_id' => $this->integer(),
            'group' => $this->integer(),
            'position' => $this->integer(),
            'status' => $this->smallInteger(),
            'status_at' => $this->integer(),
            'status_by' => $this->integer(),
            'added_at' => $this->integer(),
            'added_by' => $this->integer(),
        ], $this->tableOptions);

        $this->createIndex("{{%idx-$tableRow-owner-data}}", $table, ['owner_class_id', 'owner_instance_id']);

        $this->createIndex("ui-owner-group-position", $table, [
            'owner_class_id',
            'owner_instance_id',
            'group',
            'position',
        ], true);

        $this->addForeignKey("{{%fk-$tableRow-status_by}}", $table, 'status_by', $this->userTable, 'id');
        $this->addForeignKey("{{%fk-$tableRow-added_by}}", $table, 'added_by', $this->userTable, 'id');

        return $this;
    }

    /**
     * Удаляем таблицу файлов
     * @return $this
     */
    protected function downFileTable()
    {
        $tableRow = $this->fileTable;
        $table = $this->table($tableRow);

        $this->dropForeignKey("{{%fk-$tableRow-added_by}}", $table);
        $this->dropForeignKey("{{%fk-$tableRow-status_by}}", $table);
        $this->dropTable($table);

        return $this;
    }

    /**
     * Удаляем таблицу контент-страниц
     * @return $this
     */
    protected function downPageTable()
    {
        return $this->dropTableWithCommonColumns($this->pageTable);
    }

    /**
     * Удаляем таблицу пользователей
     * @return $this
     */
    protected function downUserTable()
    {
        return $this->dropTableWithCommonColumns($this->userTable);
    }
}
