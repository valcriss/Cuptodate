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
                $this->records[] = new DockerImage($id, $digest, DockerRepository::factory(explode("@", $image['RepoDigests'][0])[0]));
            }
        }
    }

    public function get($id): ?DockerImage
    {
        foreach($this->records as $record)
        {
            if($record->id === $id) return $record;
        }
        return null;
    }


}