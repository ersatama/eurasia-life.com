<?php

require_once __DIR__ . '/MigrationHelpersTrait.php';

use yii\db\Migration;
use console\migrations\MigrationHelpersTrait;

/**
 * Class m190323_110129_create_short_code_table – Создаем таблицу шорткодов
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class m190323_110129_create_short_code_table extends Migration
{
    use MigrationHelpersTrait;

    /**
     * @var string – таблица шорткодов
     */
    protected $shortCodeTable = 'short_code';

    /**
     * @var string – таблица пользователей
     */
    protected $userTable = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTableWithCommonColumns($this->shortCodeTable, [
            'for' => $this->string(),
            'short_code' => $this->string(),
            'content' => $this->text(),
            'type' => $this->string(),
            'label' => $this->string(),
            'hint' => $this->string(),
            'placeholder' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTableWithCommonColumns($this->shortCodeTable);
    }
}
