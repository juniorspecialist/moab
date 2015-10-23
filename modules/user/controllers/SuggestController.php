<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:36
 */

namespace app\modules\user\controllers;


use app\models\Base;
use app\models\Selections;
use app\models\SelectionsSearch;
use app\modules\user\models\SuggestForm;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;

class SuggestController extends UserMainController{

    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
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

        //находим базу на тек. страницы и выводим общие данные по базе
        $base = $this->findBase(Yii::$app->params['subscribe_suggest_and_wordstat']);

        //выбираем данные по выборкам пользователя
        $model = new SelectionsSearch();

        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
            'base'=>$base
        ]);
    }


    /*
     * форма создания задания на выборку -
     * в одном задании может быть указано несколько фраз/ключей и на каждую фразу/ключ создаётся отдельное задание
     */
    public function actionCreate()
    {

        $model = new SuggestForm();

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
     * удаляем выбранные юзером выборки, в любом статус
     */
    public function actionDelete()
    {
        if(Yii::$app->request->isPost)
        {
            //удаляем выбранные выборки по связке - user_id+ID(выбранных им выборок)
            Yii::$app->db
                ->createCommand("DELETE FROM Selections::tableName() WHERE user_id=:user_id AND id IN (:ids)")
                ->bindValues([':user_id'=>Yii::$app->user->id, ':ids'=>Yii::$app->request->post('ids')])
                ->execute();
            return 'ok';
        }
        throw new NotFoundHttpException('The requested page does not exist.');
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

    protected function findBase($id){
        if (($model = Base::findOne(['id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}