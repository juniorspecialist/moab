<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.08.15
 * Time: 16:33
 */

namespace app\modules\admin\controllers;


use app\models\Docs;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use Yii;

class DocController extends BaseAdminController{

    /*
     * список документов от юзеров
     */
    public function actionIndex(){

        $query = Docs::find()->joinWith(['user'])->orderBy('uploaded DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * контроллер для отображения фото по его имени
     */
    public function actionModal($id)
    {

        $photo = Docs::find()->where(['id'=>$id])->asArray()->one();

        if(file_exists(\Yii::getAlias('@docsUsers/').$photo['file']) && $photo)
        {
            return  Html::img('data:image/png;base64,'.base64_encode(file_get_contents(\Yii::getAlias('@docsUsers/').$photo['file'])), ['width'=>'800px']);

             Yii::$app->end();
            //echo file_get_contents(\Yii::getAlias('@docsUsers/').$photo['file']);die();
            //return Yii::$app->response->sendFile(\Yii::getAlias('@docsUsers/').$photo['file']); die();
            //return Yii::$app->response->xSendFile(\Yii::getAlias('@docsUsers/').$photo['file'])->send();
        }else{
            throw new NotFoundHttpException('Фото не найдено');
        }
    }

    public function actionAccept()
    {
        if(Yii::$app->request->post('id')){

            $doc = Docs::findOne(Yii::$app->request->post('id'));

            if($doc){
                $doc->status = Docs::STATUS_ACCEPT;
                $doc->update(false);
            }
            $this->redirect('index');
        }else{
            throw new NotFoundHttpException('Фото не найдено');
        }
    }

    public function actionCancel()
    {
        if(Yii::$app->request->post('id')){

            $doc = Docs::findOne(Yii::$app->request->post('id'));

            if($doc){
                $doc->status = Docs::STATUS_CANCEL;
                $doc->update(false);
            }
            $this->redirect('index');
        }else{
            throw new NotFoundHttpException('Фото не найдено');
        }
    }
}