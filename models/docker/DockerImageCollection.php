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
                $repositoryTag = explode("@", $image['RepoDigests'][0])[0];
                if (isset($image['RepoTags']) && count($image['RepoTags']) > 0) {
                    $repositoryTag = $image['RepoTags'][0];
                }

                $this->records[] = new DockerImage($id, $digest, DockerRepository::factory($repositoryTag));
            }
        }
    }

    public function get($id): ?DockerImage
    {
        foreach ($this->records as $record) {
            if ($record->id === $id) return $record;
        }
        return null;
    }


}