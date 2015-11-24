<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:36
 */

namespace app\modules\user\controllers;


use app\models\Base;
use app\models\Category;
use app\models\MinusWords;
use app\models\Preview;
use app\models\Selections;
use app\models\SelectionsSuggestSearch;
use app\modules\user\models\SelectionsSuggest;
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

        //форма добавления быстрой выборки
        $formModel = new SuggestForm();

        $formModel->setScenario('suggest-pro');

        $formModel->source_words_count_from = 1;
        $formModel->source_words_count_to = 32;
        $formModel->position_from = 1;
        $formModel->position_to = 10;
        $formModel->potential_traffic = Selections::POTENCIAL_TRAFFIC_ANYONE;
        $formModel->suggest_words_count_from = 1;
        $formModel->suggest_words_count_to = 32;
        $formModel->length_from = 1;
        $formModel->length_to = 256;
        $formModel->category_id = Category::getWithOutGroup();
        $formModel->need_wordstat = 1;

        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {

            //создаём выборки
            $formModel->createSelects();

            return $this->refresh();
        }

        //выбираем данные по выборкам пользователя(по базе SUGGEST)
        $model = new SelectionsSuggestSearch();

        $dataProvider = $model->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->isAjax){

            $answer = [];

            $models = $dataProvider->getModels();

            foreach ($models as $model) {
                $answer[] = [
                    'id' => $model->id,
                    'status' => $model->getStatusGrid(),
                    'result_count' => $model->getResultCountGrid(),
                    'preview' => $model->getPreviewGrid(),
                    'download' => $model->getLinkGrid(),
                    'params'=> $model->getParamsInfo(),
                ];
            }
            return json_encode($answer);
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
            //находим базу на тек. страницы и выводим общие данные по базе
            'base'=>$this->base,
            'model'=>$formModel,
        ]);
    }

    /*
     * предварительный просмотр результатов выборки
     */
    public function actionPreview($id)
    {

        //проверим свою ли выборку юзер хочет посмотреть
        $selections = $this->loadSelect($id);

        if($selections->selections->status !== Selections::STATUS_DONE)
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        //определяем какие параметры нам нужны
        $fields = ['phrase','length','position'];

        //если выбран параметр не выводить данные по вордстату - то и не показываем стобцы и не выбираем в запросе данные эти
        if($selections->need_wordstat == \app\models\Selections::YES){
            $fields = ArrayHelper::merge($fields,['wordstat_1','wordstat_3']);
        }

        //формируем запрос на выборку ТОЛЬКО необходимых данных
        $query = Preview::find()
            ->select($fields)
            ->where([
                'selection_id'=>$selections->selections->id
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
            $category = Yii::$app->db
                ->createCommand('SELECT id FROM category WHERE user_id=:user_id AND id=:id')
                ->bindValues([':id'=>Yii::$app->request->post('category_id'), ':user_id'=>Yii::$app->user->id])
                ->queryScalar();

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

        $model->setScenario('suggest-pro');

        $model->source_words_count_from = 1;
        $model->source_words_count_to = 32;
        $model->position_from = 1;
        $model->position_to = 10;


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            //создаём выборки
            $model->createSelects();

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

                //сами выборки помечаем как удалённые
                Selections::updateAll(['is_del'=>1],['in','id',$rows]);

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

        //связываем таблицу выборок - SUGGEST с общей таблицей выборок
        $model = SelectionsSuggest::find()
            ->joinWith('selections')
            ->where(['selections.id'=>$id, 'selections.user_id'=>Yii::$app->user->id])
            ->one();

        if ($model!== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}