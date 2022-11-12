<?php


namespace app\models\docker;


class DockerContainer
{
    public string $id;
    public string $name;
    public string $digest;
    public DockerRepository $repository;

    public function __construct(string $id, string $name, DockerRepository $repository, string $digest)
    {
        $this->id = $id;
        $this->name = $name;
        $this->repository = $repository;
        $this->digest = $digest;
    }

    public static function factory(array $container, DockerImage $image)
    {
        $name = isset($container['Labels']['com.docker.compose.project']) && isset($container['Labels']['com.docker.compose.service']) ? $container['Labels']['com.docker.compose.project'] . "_" . $container['Labels']['com.docker.compose.service'] : $container['Names'][0];
        return new DockerContainer($container['Id'], $name, $image->repository, $image->digest);
    }
}