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
use yii\web\Response;
use yii\widgets\ActiveForm;


class SuggestController extends UserMainController{

    private $_base;

    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete','preview', 'change-category'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function getBase(){
        if($this->_base == null){
            $this->_base = $this->findBase(Yii::$app->params['subscribe_suggest_and_wordstat']);
        }
        return $this->_base;
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

        if(Yii::$app->request->isAjax){

            return $this->renderAjax('_grid',['dataProvider' => $dataProvider]);
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
            //находим базу на тек. страницы и выводим общие данные по базе
            'base'=>$this->base,
        ]);
    }

    /*
     * предварительный просмотр результатов выборки
     */
    public function actionPreview($id)
    {

        //проверим свою ли выборку юзер хочет посмотреть
        $selections = $this->loadSelect($id);

        if($selections->status !== Selections::STATUS_DONE)
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        //определяем какие параметры нам нужны
        $fields = ['phrase','length','position'];

        //если выбран параметр не выводить данные по вордстату - то и не показываем стобцы и не выбираем в запросе данные эти
        if($selections->need_wordstat == \app\models\Selections::YES){
            $fields = ArrayHelper::merge($fields,['wordstat_1','wordstat_3']);
        }


        $query = Preview::find()
            ->select($fields)
            ->where([
                'selection_id'=>$selections->id
            ]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            //'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('preview', ['dataProvider'=>$provider, 'base'=>$this->base, 'model'=>$selections]);
    }

    /*
     * перемещение выборок в другую группу
     * получаем список выбранных выборок из таблицы+получаем ID категории выборок
     */
    public function actionChangeCategory(){

        //проверка наличия параметров
        if(Yii::$app->request->isPost && Yii::$app->request->post('ids') && Yii::$app->request->post('category_id'))
        {

            //убедимся, что юзер выбрал существующую категорию
            $category = Yii::$app->db->createCommand('SELECT id FROM category WHERE user_id=:user_id AND id=:id')->bindValues([':id'=>Yii::$app->request->post('category_id'), ':user_id'=>Yii::$app->user->id])->queryScalar();

            if($category){
                //обновим подвязку к новой категории
                Selections::updateAll(['category_id'=>$category],['in', 'id', Yii::$app->request->post('ids')]);

                return true;
            }

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


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            //создаём выборки
            $model->createSelects();

            //Yii::$app->getSession()->setFlash('success', 'Успешно добавили выборку(и)');

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

        if(Yii::$app->request->isPost && Yii::$app->request->post('ids'))
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