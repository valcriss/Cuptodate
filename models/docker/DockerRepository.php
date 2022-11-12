<?php


namespace app\models\docker;


class DockerRepository
{
    public string $name;
    public string $namespace;
    public string $repository;
    public string $tag;

    public function __construct(string $name, string $namespace, string $repository, string $tag)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->repository = $repository;
        $this->tag = $tag;
    }

    public static function factory(string $repositoryTag)
    {
        $hasNamespace = (strpos($repositoryTag, "/") !== false);
        $hasTag = (strpos($repositoryTag, ":") !== false);

        $namespace = ($hasNamespace) ? explode("/", $repositoryTag)[0] : "library";

        if ($hasNamespace && $hasTag) {
            $repository = explode(":", explode("/", $repositoryTag)[1])[0];
            $tag = explode(":", explode("/", $repositoryTag)[1])[1];
        } elseif (!$hasNamespace && $hasTag) {
            $repository = explode(":", $repositoryTag)[0];
            $tag = explode(":", $repositoryTag)[1];
        } elseif ($hasNamespace && !$hasTag) {
            $repository = explode("/", $repositoryTag)[1];
            $tag = "latest";
        } else {
            $repository = $repositoryTag;
            $tag = "latest";
        }

        return new DockerRepository($repositoryTag, $namespace, $repository, $tag);
    }
}