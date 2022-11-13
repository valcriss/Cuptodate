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
        $namespace = self::getNamespace($repositoryTag);
        $repository = self::getRepository($repositoryTag);
        $tag = self::getTag($repositoryTag);

        return new DockerRepository($repositoryTag, $namespace, $repository, $tag);
    }

    private static function getNamespace(string $repositoryTag): string
    {
        $hasNamespace = (strpos($repositoryTag, "/") !== false);
        if (!$hasNamespace) return "library";
        $tab = explode("/", $repositoryTag);

        return $tab[count($tab) - 1];
    }

    private static function getRepository(string $repositoryTag): string
    {
        $hasNamespace = (strpos($repositoryTag, "/") !== false);
        $hasTag = (strpos($repositoryTag, ":") !== false);
        $startingIndex = ($hasNamespace) ? strrpos($repositoryTag, "/") + 1 : 0;
        $endIndex = ($hasTag) ? strpos($repositoryTag, ":") : strlen($repositoryTag);
        return substr($repositoryTag, $startingIndex, $endIndex - $startingIndex);
    }

    private static function getTag(string $repositoryTag): string
    {
        $hasTag = (strpos($repositoryTag, ":") !== false);
        if (!$hasTag) return "latest";
        return explode(":", $repositoryTag)[1];
    }
}