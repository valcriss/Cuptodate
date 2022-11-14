<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\ContainersUpdater;
use app\models\docker\DockerClient;
use app\models\LookupRemoteUpdater;
use app\models\RepositoriesUpdater;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UpdateController extends Controller
{

    public function actionIndex()
    {
        $interval = (getenv("INTERNAL_UPDATE_INTERVAL") !== null && is_int(getenv("INTERNAL_UPDATE_INTERVAL"))) ? intval(getenv("INTERNAL_UPDATE_INTERVAL")) : 5;
        while (true) {
            try {
                $client = new DockerClient();
                $containers = $client->listContainers();
                RepositoriesUpdater::updateFromContainers($containers);
                ContainersUpdater::updateFromContainers($containers);
                LookupRemoteUpdater::update();
            } catch (\Exception $e) {
                echo "EXCEPTION:" . $e->getMessage() . "\n";
                echo $e->getTraceAsString() . "\n\n";
            }
            sleep($interval * 60);
        }
    }
}
