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
        $containers = $this->apiGetCall("containers/json");
        $images = $this->apiGetCall("images/json", "digests=true");
        if ($containers === false || $images === false) {
            echo "Error : Unable to access docker api\n";
            return [];
        }
        $imageCollection = new DockerImageCollection($images);

        foreach ($containers as $container) {
            $image = $imageCollection->get($container['ImageID']);
            if ($image !== null) {
                $result[] = DockerContainer::factory($container, $image);
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
            echo "api calls returned curl error : " . curl_error($ch) . "\n";
            return false;
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (intval($http_code) !== 200) {
            echo "api calls returned httpcode $http_code : " . $result . "\n";
            return false;
        }

        curl_close($ch);
        return json_decode($result, true);
    }
}
