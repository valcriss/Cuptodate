<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "container".
 *
 * @property int $id
 * @property string $containerId
 * @property string $name
 * @property string $digest
 * @property int $repository_id
 * @property string $creationDate
 * @property string $updateDate
 *
 * @property Repository $repository
 */
class Container extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'container';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['containerId', 'name', 'digest', 'repository_id', 'creationDate', 'updateDate'], 'required'],
            [['repository_id'], 'integer'],
            [['creationDate', 'updateDate'], 'safe'],
            [['containerId', 'name', 'digest'], 'string', 'max' => 255],
            [['containerId'], 'unique'],
            [['repository_id'], 'exist', 'skipOnError' => true, 'targetClass' => Repository::class, 'targetAttribute' => ['repository_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'containerId' => 'Container ID',
            'name' => 'Name',
            'digest' => 'Digest',
            'repository_id' => 'Repository ID',
            'creationDate' => 'Creation Date',
            'updateDate' => 'Update Date',
        ];
    }

    /**
     * Gets query for [[Repository]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRepository()
    {
        return $this->hasOne(Repository::class, ['id' => 'repository_id']);
    }
}
