<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:36
 */

namespace app\modules\user\controllers;


use app\models\Base;
use app\models\MinusWords;
use app\models\Preview;
use app\models\Selections;
use app\models\SelectionsSearch;
use app\modules\user\models\SuggestForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
                        'actions' => ['index', 'create', 'delete','preview'],
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
     * предварительный просмотр результатов выборки
     */
    public function actionPreview($id)
    {

        //TODO доделать вывод информации через диалоговое окно в таблице выборок
        if(Yii::$app->request->isPost)
        {
            $cache_id = 'preview_suggest_'.$id;

            //проверим свою ли выборку юзер хочет посмотреть
            $selections = $this->loadSelect($id);

            if($selections !== Selections::STATUS_DONE)
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            //если результат выборки закеширован - получаем из кеша
            $data = Yii::$app->cache->get($cache_id);

            // данные не закешированы - производим выборку
            if ($data === false) {

                $query = Preview::find()
                    ->select(['phrase','length','position','wordstat_1','wordstat_2','wordstat_3'])
                    ->where([
                        'user_id' => Yii::$app->user->id,
                        'selection_id'=>$selections->id
                    ])
                    ->limit(1000);

                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => false,
                    'sort' => false,
                ]);

                $data = $this->render('preview', ['dataProvider'=>$provider]);

                // store $data in cache so that it can be retrieved next time
                Yii::$app->cache->set($cache_id, $data);
            }

            return $data;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /*
     * форма создания задания на выборку -
     * в одном задании может быть указано несколько фраз/ключей и на каждую фразу/ключ создаётся отдельное задание
     */
    public function actionCreate()
    {

        $model = new SuggestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            //создаём выборки
            $model->createSelects();

            Yii::$app->getSession()->setFlash('success', 'Успешно добавили выборку(и)');

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
            //находим список совпадений выборок, принадлежащих текущему юзеру(чтобы юзер лишь свои выборки мог удалить)
            $rows = (new \yii\db\Query())
                ->select(['id'])
                ->from('selections')
                ->where(['id' => Yii::$app->request->post('ids'),'user_id'=>Yii::$app->user->id])
                ->all();

            if($rows){

                foreach($rows as $id){
                    Selections::findOne(['id'=>$id['id']])->delete();
                }

                return true;
            }

            return false;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * проверка доступа пользователя к БД
     */
    protected function access()
    {
        if(!\app\modules\user\models\User::isSubscribeMoab(Yii::$app->params['subscribe_suggest_and_wordstat'])){
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

    protected function loadSelect($id){
        if (($model = Selections::findOne(['id'=>$id, 'user_id'=>Yii::$app->user->id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}