<?php

use yii\db\Migration;

/**
 * Class m200401_075341_add_fields_to_blog_table
 */
class m200401_075341_add_fields_to_blog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blog}}', 'language_id', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blog}}', 'language_id');
    }
}
