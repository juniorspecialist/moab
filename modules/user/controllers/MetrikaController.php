<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.15
 * Time: 16:29
 */

namespace app\modules\user\controllers;


use app\models\Category;
use app\models\Selections;
use app\models\SelectionsSearch;
use app\modules\user\models\MetrikaForm;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\web\View;


class MetrikaController extends UserMainController{


    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /*
     * информация о выборках из БД - Моаб-Метрика
     */
    public function actionIndex()
    {
        //проверка доступа к выборкам для тек. юзера
        $this->access();

        //выбираем данные по выборкам пользователя

        $model = new SelectionsSearch();

        $dataProvider = $model->search(Yii::$app->request->queryParams);


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
        ]);
    }

    /*
     * форма создания задания на выборку -
     * в одном задании может быть указано несколько фраз/ключей и на каждую фразу/ключ создаётся отдельное задание
     */
    public function actionCreate()
    {

        $model = new MetrikaForm();

        /*
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }*/

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            Yii::$app->getSession()->setFlash('success', 'Успешно добавили новую запись.');

            return $this->redirect(['index']);

        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /*
     * проверка доступа пользователя к БД
     */
    protected function access()
    {
        if(!\app\modules\user\models\User::isSubscribeMoab()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}