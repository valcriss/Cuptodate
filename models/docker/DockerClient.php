<?php


namespace app\models\docker;


class DockerClient
{
    private string $unixSocket;
    private string $dockerApiVersion;

    public function __construct()
    {
        $this->unixSocket = "/var/run/docker.sock";
        $this->dockerApiVersion = getenv("DOCKER_API_VERSION");
    }

    /**
     * @return DockerContainer[]
     */
    public function listContainers(): array
    {
        $result = [];
        $containers = $this->apiGetCall("containers/json", "all=true");
        $images = $this->apiGetCall("images/json", "digests=true");

        $imageCollection = new DockerImageCollection($images);

        foreach ($containers as $container) {
            $image = $imageCollection->get($container['ImageID']);
            if ($image !== null) {
                $result[$container['Id']] = DockerContainer::factory($container, $image);
            }
        }

        return $result;
    }

    private function apiGetCall(string $method, ?string $params = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/" . $this->dockerApiVersion . "/" . $method . ($params !== null ? "?" . $params : null));
        curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, $this->unixSocket);
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
        return json_decode($result, true);
    }
}