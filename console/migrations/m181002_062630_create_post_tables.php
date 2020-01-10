<?php

use yii\db\Migration;
use console\migrations\MigrationHelpersTrait;

/**
 * Class m181002_062630_create_post_tables – Создаем таблицы для публикаций/новостей
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class m181002_062630_create_post_tables extends Migration
{
    use MigrationHelpersTrait;

    /**
     * @var string – таблица публикаций
     */
    protected $postArticleTable = 'post_article';

    /**
     * @var string – таблица пользователей
     */
    protected $userTable = 'user';

    /**
     * @var string – таблица файлов
     */
    protected $fileTable = 'file';

    /**
     * {@inheritdoc}
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->upPostArticleTable();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->downPostArticleTable();
    }

    /**
     * @return $this
     * @throws \yii\base\NotSupportedException
     */
    protected function upPostArticleTable()
    {
        $tableRaw = $this->postArticleTable;
        $table = $this->table($tableRaw);

        $this->createTableWithCommonColumns($tableRaw, [
            'lang_id' => $this->integer(),
            'name' => $this->string(),
            'slug' => $this->string(),
            'announce' => $this->text(),
            'content' => $this->mediumText(),
            'main_image_file_id' => $this->integer(),
            'main_image_url' => $this->string(),
            'publish_at' => $this->integer(),
            'position' => $this->integer(),
            'views' => $this->integer(),
            'visible' => $this->boolean(),
            'soc_title' => $this->string(),
            'soc_content' => $this->string(),
            'soc_image_id' => $this->integer(),
            'lang_ru_id' => $this->integer(),
            'lang_kz_id' => $this->integer(),
            'lang_en_id' => $this->integer(),
        ]);

        $this->addForeignKey("{{%fk-$tableRaw-main_image_file_id}}", $table, 'main_image_file_id', $this->fileTable, 'id');
        $this->addForeignKey("{{%fk-$tableRaw-soc_image_id}}", $table, 'soc_image_id', $this->fileTable, 'id');
        $this->createIndex("{{%idx-$tableRaw-position}}", $table, 'position', true);

        return $this;
    }

    /**
     * @return $this
     */
    protected function downPostArticleTable()
    {
        return $this->dropTableWithCommonColumns($this->postArticleTable, [
            'soc_image_id',
            'main_image_file_id',
        ]);
    }
}
