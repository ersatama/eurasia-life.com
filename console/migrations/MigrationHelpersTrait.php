<?php

namespace console\migrations;

/**
 * !!! Внимание: Изменяя этот файл проверяйте все миграции.
 * - Если сломать хэлпер, то с нуля миграции не поднимутся!!!
 * - Если нужно что-то другое сделать, то или отдельный метод или в нужной миграции вручную делать
 * Trait MigrationHelpersTrait - Помогайки миграций
 *
 * @mixin \yii\db\Migration
 *
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait MigrationHelpersTrait
{
    /**
     * @var string – опции для таблиц
     */
    protected $tableOptions;

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     *
     * Название таблицы
     * @param $tableRaw
     * @return string
     */
    protected function table($tableRaw)
    {
        return "{{%$tableRaw}}";
    }

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     *
     * Создает таблицу с общими колонками и внешними ключами к ним
     * @param string $tableRaw
     * @param array $columns
     * @param null|string $options
     * @return $this
     */
    protected function createTableWithCommonColumns($tableRaw, array $columns, $options = null)
    {
        $table = $this->table($tableRaw);

        $columns = array_merge(['id' => $this->primaryKey()], $columns, [
            'status' => $this->smallInteger(),
            'status_at' => $this->integer(),
            'status_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);

        if ($options === null) {
            $options = $this->tableOptions;
        }

        $this->createTable($table, $columns, $options);

        $this->addForeignKey("{{%fk-$tableRaw-status_by}}", $table, 'status_by', $this->userTable, 'id');
        $this->addForeignKey("{{%fk-$tableRaw-updated_by}}", $table, 'updated_by', $this->userTable, 'id');
        $this->addForeignKey("{{%fk-$tableRaw-created_by}}", $table, 'created_by', $this->userTable, 'id');

        return $this;
    }

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     *
     * Удаляет таблицу с общими колонками и внешними ключами к ним
     * @param string $tableRaw
     * @param array $foreignKeyColumnNames – название колонок по которым нужно дополнительно удалить внешние ключи
     * @return $this
     */
    protected function dropTableWithCommonColumns($tableRaw, array $foreignKeyColumnNames = [])
    {
        $table = $this->table($tableRaw);

        foreach ($foreignKeyColumnNames as $columnName) {
            $this->dropForeignKey("{{%fk-$tableRaw-$columnName}}", $table);
        }

        $this->dropForeignKey("{{%fk-$tableRaw-created_by}}", $table);
        $this->dropForeignKey("{{%fk-$tableRaw-updated_by}}", $table);
        $this->dropForeignKey("{{%fk-$tableRaw-status_by}}", $table);

        $this->dropTable($table);

        return $this;
    }

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     *
     * @return \yii\db\ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    protected function mediumText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
    }

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     *
     * @return \yii\db\ColumnSchemaBuilder
     * @throws \yii\base\NotSupportedException
     */
    protected function longText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
    }

    /**
     * !!! Не изменять !!! могут сломаться старые миграции
     * Опции таблиц по-умолчанию
     */
    protected function initDefaultTableOptions()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
    }
}
