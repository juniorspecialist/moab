<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.11.15
 * Time: 23:19
 */

namespace app\modules\admin\controllers;


use app\models\Financy;
use app\models\FinancySearch;
use Yii;
use app\models\Base;
use yii\data\ActiveDataProvider;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\BaseAdminController;

class FinancyController extends BaseAdminController{

    /**
     * Lists all Base models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new FinancySearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }
}