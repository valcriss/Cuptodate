<?php

use yii\db\Migration;

/**
 * Class m221112_195908_repositories
 */
class m221112_195908_repositories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("repository", [
            "id" => $this->primaryKey(),
            "name" => $this->string(255)->notNull()->unique(),
            "namespace" => $this->string(100)->notNull(),
            "repository" => $this->string(100)->notNull(),
            "tag" => $this->string(100)->notNull(),
            "lookupDate" => $this->dateTime()->null()->defaultValue(null),
            "remoteDigest" => $this->string(255)->null()->defaultValue(null),
            "creationDate" => $this->dateTime()->notNull(),
            "updateDate" => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("repository");
    }
}
