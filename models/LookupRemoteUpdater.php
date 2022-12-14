<?php


namespace app\models;


use app\models\database\Repository;

class LookupRemoteUpdater
{


    public static function update()
    {
        $doNotLookForUpdateBefore = (getenv("DO_NOT_LOOK_FOR_UPDATE_BEFORE") !== null) ? intval(getenv("DO_NOT_LOOK_FOR_UPDATE_BEFORE")) : 30;
        $repositories = Repository::find()->all();
        /** @var Repository[] $repositories */
        foreach ($repositories as $repository) {
            if ($repository->lookupDate !== null && (new \DateTime())->getTimestamp() - strtotime($repository->lookupDate) < ($doNotLookForUpdateBefore * 60)) continue;
            self::updateRepository($repository);
        }
    }

    private static function updateRepository(Repository $repository)
    {
        $remoteDigest = self::fetchDigest($repository->namespace, $repository->repository, $repository->tag);
        if ($remoteDigest === false) return;
        $repository->lookupDate = (new \DateTime())->format("Y-m-d H:i:s");
        if ($repository->remoteDigest !== $remoteDigest) {
            $repository->remoteDigest = $remoteDigest;
            $repository->updateDate = (new \DateTime())->format("Y-m-d H:i:s");
        }
        $repository->save();
    }

    private static function fetchDigest($namespace, $repository, $tag)
    {
        $page = 1;
        while (true) {
            $remoteApiUrl = "https://hub.docker.com/v2/namespaces/$namespace/repositories/$repository/tags?page=$page";
            $content = self::apiCall($remoteApiUrl);
            if ($content === false) return false;
            foreach ($content->results as $result) {
                if (property_exists($result, "tag_status") && $result->tag_status != "active") continue;
                if ($result->name === $tag) {
                    if (!property_exists($result, "digest") && property_exists($result, "images") && count($result->images) > 0) {
                        return $result->images[0]->digest;
                    } else if (property_exists($result, "digest")) {
                        return $result->digest;
                    } else {
                        echo "$remoteApiUrl\n";
                    }
                    return false;
                }
            }
            $page++;
        }
        return false;
    }

    private static function apiCall($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (intval($http_code) !== 200) {
            return false;
        }

        curl_close($ch);
        return json_decode($result);
    }
}
