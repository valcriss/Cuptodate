<?php


namespace app\models;


use app\models\database\Container;
use app\models\database\Repository;
use app\models\docker\DockerContainer;
use app\models\docker\DockerRepository;

class RepositoriesUpdater
{
    /**
     * @param DockerContainer[] $containers
     * @return bool
     */
    public static function updateFromContainers(array $containers): bool
    {
        if (!is_array($containers)) return false;

        $existingRepositories = [];

        foreach ($containers as $container) {
            $existingRepositories[] = $container->repository->name;
            if (!self::update($container->repository)) return false;
        }

        self::cleanup($existingRepositories);

        return true;
    }

    private static function cleanup(array $existingRepositories)
    {
        if (!is_array($existingRepositories) || count($existingRepositories) === 0) return;
        Container::deleteAll("repository_id IN (SELECT id FROM repository WHERE name NOT IN ('" . implode("','", $existingRepositories) . "'))");
        Repository::deleteAll("name NOT IN ('" . implode("','", $existingRepositories) . "')");
    }

    private static function update(DockerRepository $repository)
    {
        $databaseRecord = Repository::find()->where(["name" => $repository->name])->one();
        if ($databaseRecord !== null) return true;

        $databaseRecord = new Repository();
        $databaseRecord->name = $repository->name;
        $databaseRecord->namespace = $repository->namespace;
        $databaseRecord->repository = $repository->repository;
        $databaseRecord->tag = $repository->tag;
        $databaseRecord->lookupDate = null;
        $databaseRecord->remoteDigest = null;
        $databaseRecord->creationDate = (new \DateTime())->format("Y-m-d H:i:s");
        $databaseRecord->updateDate = (new \DateTime())->format("Y-m-d H:i:s");

        return $databaseRecord->save();
    }
}