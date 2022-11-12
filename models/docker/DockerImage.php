<?php


namespace app\models\docker;


class DockerImage
{
    public string $id;
    public string $digest;
    public DockerRepository $repository;

    public function __construct(string $id,string $digest,DockerRepository $repository)
    {
        $this->id = $id;
        $this->digest = $digest;
        $this->repository = $repository;
    }
}