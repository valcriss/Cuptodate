<?php


namespace app\models;


use app\models\database\Container;
use app\models\database\Repository;
use app\models\docker\DockerContainer;

class ContainersUpdater
{
    /**
     * @param DockerContainer[] $containers
     * @return bool
     */
    public static function updateFromContainers(array $containers): bool
    {
        if (!is_array($containers)) return false;

        $existingContainers = [];

        foreach ($containers as $container) {
            $existingContainers[] = $container->id;
            if (!self::update($container)) return false;
        }

        self::cleanup($existingContainers);

        return true;
    }

    private static function cleanup(array $existingContainers)
    {
        if (!is_array($existingContainers) || count($existingContainers) === 0) return;
        Container::deleteAll("containerId NOT IN ('" . implode("','", $existingContainers) . "')");
    }

    private static function update(DockerContainer $container)
    {
        $databaseRecord = Container::find()->where(["containerId" => $container->id])->one();
        if ($databaseRecord !== null) return true;
        $repository = Repository::find()->where(["name" => $container->repository->name])->one();
        /** @var Repository $repository */
        if ($repository === null) return false;
        $databaseRecord = new Container();
        $databaseRecord->containerId = $container->id;
        $databaseRecord->name = $container->name;
        $databaseRecord->digest = $container->digest;
        $databaseRecord->repository_id = $repository->id;
        $databaseRecord->creationDate = (new \DateTime())->format("Y-m-d H:i:s");
        $databaseRecord->updateDate = (new \DateTime())->format("Y-m-d H:i:s");

        return $databaseRecord->save();
    }
}