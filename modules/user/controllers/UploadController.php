<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.11.15
 * Time: 14:25
 */

namespace app\modules\user\controllers;


use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\modules\user\models\UploadFele;

class UploadController extends UserMainController{


    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /*
     * пользователь загружает файл с  данными
     * suggest, suggest-pro
     * обрабатываем содержимое файла и возвращаем результат
     */
    public function actionUpload(){

        $model = new UploadFele();

        if (\Yii::$app->request->isPost) {

            //проверка на разрешенные типы файлов
            if(!in_array(\Yii::$app->request->get('type'), ['txt','csv'])){
                throw new NotFoundHttpException('The requested page does not exist.');
            }


            $model->extensions = \Yii::$app->request->get('type');

            $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');

            if ($model->upload()) {
                // file is uploaded successfully
                return $model->content;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}