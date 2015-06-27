<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.15
 * Time: 16:15
 */

namespace app\modules\admin\controllers;


use app\models\Financy;
use app\modules\user\controllers\AdminController;
use yii\data\ActiveDataProvider;

class ChekController extends AdminController
{


    /*
     * список всех финансовых операций по всем пользователям
     */
    public function actionIndex()
    {
        $query = Financy::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['user']);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}