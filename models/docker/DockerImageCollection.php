<?php


namespace app\models\docker;


class DockerImageCollection
{
    private array $records;

    public function __construct($images)
    {
        $this->records = [];
        if (is_array($images)) {
            foreach ($images as $image) {
                $id = $image['Id'];
                $digest = explode("@", $image['RepoDigests'][0])[1];
                $this->records[] = new DockerImage($id, $digest, DockerRepository::factory($image['RepoTags'][0]));
            }
        }
    }

    public function get($id): ?DockerImage
    {
        if (isset($this->records[$id])) return $this->records[$id];
        return null;
    }


}