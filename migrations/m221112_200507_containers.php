<?php

use yii\db\Migration;

/**
 * Class m221112_200507_containers
 */
class m221112_200507_containers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("container", [
            "id" => $this->primaryKey(),
            "containerId" => $this->string(255)->notNull()->unique(),
            "name" => $this->string(255)->notNull(),
            "digest" => $this->string(255)->notNull(),
            "repository_id" => $this->integer()->notNull(),
            "creationDate" => $this->dateTime()->notNull(),
            "updateDate" => $this->dateTime()->notNull(),
        ]);

        $this->createIndex(
            'idx-container-repository_id',
            'container',
            'repository_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-container-repository_id',
            'container',
            'repository_id',
            'repository',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-container-repository_id", "container");
        $this->dropIndex("idx-container-repository_id", "container");
        $this->dropTable("container");
    }
}
