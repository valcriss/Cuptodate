<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "repository".
 *
 * @property int $id
 * @property string $name
 * @property string $namespace
 * @property string $repository
 * @property string $tag
 * @property string|null $lookupDate
 * @property string|null $remoteDigest
 * @property string $creationDate
 * @property string $updateDate
 *
 * @property Container[] $containers
 */
class Repository extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repository';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'namespace', 'repository', 'tag', 'creationDate', 'updateDate'], 'required'],
            [['lookupDate', 'creationDate', 'updateDate'], 'safe'],
            [['name', 'remoteDigest'], 'string', 'max' => 255],
            [['namespace', 'repository', 'tag'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'namespace' => 'Namespace',
            'repository' => 'Repository',
            'tag' => 'Tag',
            'lookupDate' => 'Lookup Date',
            'remoteDigest' => 'Remote Digest',
            'creationDate' => 'Creation Date',
            'updateDate' => 'Update Date',
        ];
    }

    /**
     * Gets query for [[Containers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContainers()
    {
        return $this->hasMany(Container::class, ['repository_id' => 'id']);
    }
}
