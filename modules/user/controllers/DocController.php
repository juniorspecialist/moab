<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.08.15
 * Time: 16:44
 */

namespace app\modules\user\controllers;


//use app\modules\user\controllers\UserMainController;
use app\models\Docs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use Yii;

class DocController extends UserMainController
{

    /*
     * выводим список доков по юзеру
     */
    public function actionIndex(){

        $query = Docs::find()->where(['user_id'=>\Yii::$app->user->id])->orderBy('uploaded DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);


        $model = new Docs();

        if (\Yii::$app->request->isPost) {

            $model->passport = UploadedFile::getInstance($model, 'passport');

            $model->passport_in_hands = UploadedFile::getInstance($model, 'passport_in_hands');

            $model->drive_passport = UploadedFile::getInstance($model, 'drive_passport');

            if ($model->upload()) {
                // file is uploaded successfully
                return $this->refresh();
            }
        }

        return $this->render('index', ['model'=>$model, 'dataProvider'=>$dataProvider]);
    }

    /*
     * контроллер для отображения фото по его имени
     */
    public function actionModal($id)
    {

        if(Yii::$app->request->isAjax){

            $photo = $this->loadDoc($id);

            if(file_exists(\Yii::getAlias('@docsUsers/').$photo['file']) && $photo)
            {
                return  Html::img('data:image/png;base64,'.base64_encode(file_get_contents(\Yii::getAlias('@docsUsers/').$photo['file'])), ['width'=>'800px']);

                \Yii::$app->end();

            }else{
                throw new NotFoundHttpException('Фото не найдено');
            }
        }else{
            throw new NotFoundHttpException('Фото не найдено');
        }

    }

    private function loadDoc($id)
    {
        if (($model = Docs::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}