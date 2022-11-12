<?php

namespace app\controllers;

use app\models\database\Container;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $containers = Container::find()->orderBy('name')->all();
        return $this->render('index', ["containers" => $containers]);
    }

}
