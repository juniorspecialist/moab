<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 10:12
 */

namespace app\modules\user\controllers;


use app\models\Category;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class CategoryController extends UserMainController{


    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /*
     * выводим список категорий пользователя
     */
    public function actionIndex()
    {


//        if(\Yii::$app->request->isAjax)
//        {
            $model = new Category();

            if(\Yii::$app->request->isPost)
            {
                //редактирование категории-группы
                if(\Yii::$app->request->post('pk'))
                {
                    $model = $this->loadCategory(\Yii::$app->request->post('pk'));
                    if($model){
                        $model->title = \Yii::$app->request->post('value');
                    }
                }else{
                    $model->load(\Yii::$app->request->post());
                }
                /* &&*/
                if (/*) &&*/ $model->validate()) {
                    $model->save();
                    $model = new Category();
                    //\Yii::$app->getSession()->setFlash('success', 'Успешно обновили запись.');
                }
            }

            /*
             * список категорий пользователя
             */
            $dataProvider = new ActiveDataProvider([
                'query' => Category::find()->where(['user_id'=>\Yii::$app->user->id])->orderBy('id DESC'),
                'pagination' =>false,
                'sort'=>false,
            ]);

        if(\Yii::$app->request->isAjax ||\Yii::$app->request->isPost)
        {
            return $this->renderAjax('index',['dataProvider'=>$dataProvider, 'model'=>$model]);
        }else{
            return $this->render('index',['dataProvider'=>$dataProvider, 'model'=>$model]);
        }

        //}

        //throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionDelete()
    {
        if(\Yii::$app->request->isPost && \Yii::$app->request->post('id'))
        {
            $category = $this->loadCategory(\Yii::$app->request->post('id'))->delete();

            $model = new Category();
            /*
             * список категорий пользователя
             */
            $dataProvider = new ActiveDataProvider([
                'query' => Category::find()->where(['user_id'=>\Yii::$app->user->id])->orderBy('id DESC'),
                'pagination' =>false,
                'sort'=>false,
            ]);
            return $this->renderAjax('index',['dataProvider'=>$dataProvider, 'model'=>$model]);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function loadCategory($id)
    {
        if (($model = Category::findOne(['user_id'=>\Yii::$app->user->id,'id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}