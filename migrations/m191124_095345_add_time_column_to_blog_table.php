<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%blog}}`.
 */
class m191124_095345_add_time_column_to_blog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blog}}', 'create_time', $this->dateTime());
        $this->addColumn('{{%blog}}', 'update_time', $this->dateTime());
        $this->addColumn('{{%blog}}', 'image', $this->string(150)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blog}}', 'image');
        $this->dropColumn('{{%blog}}', 'update_time');
        $this->dropColumn('{{%blog}}', 'create_time');
    }
}
