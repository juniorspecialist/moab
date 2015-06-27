<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.15
 * Time: 13:27
 */

namespace app\modules\admin\controllers;


use app\models\Access;
use app\modules\admin\controllers\BaseAdminController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;


class AccountController extends BaseAdminController{

    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' => Access::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(){

    }

    public function actionUpdate($id){

        $model = $this->findModel($id);

        $model->setScenario('create');

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

            \Yii::$app->getSession()->setFlash('success', 'Успешно обновили данные.');

            return $this->redirect(['index']);
        } else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Base model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        \Yii::$app->getSession()->setFlash('success', 'Успешно удалили запись.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Base model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Base the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Access::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * пакетная загрузка аккуантов
     */
    public function actionUpload(){

        $model = new Access();

        $model->setScenario('upload');

        //login;password;server

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //распарсимем структуру и записываем новые данные
            $list = explode(PHP_EOL, $model->upload);

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                foreach($list as $j=>$row){
                    $info = explode(';', $row);
                    if($info){
                        $access = new Access();
                        $access->setScenario('create');
                        $access->login = $info[0];
                        $access->pass = $info[1];
                        $access->server= $info[2];

                        if($access->validate()){
                            $access->save();
                        }else{
                            $model->addError('upload', 'Строка №'.($j+1).print_r($access->errors,true));
                        }
                    }
                }

                if(!$model->hasErrors())
                {
                    //$transaction->rollBack();
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Успешно добавили новые данные.');
                    return $this->redirect(['index']);
                }

            } catch(\Exception $e) {


                \Yii::$app->getSession()->setFlash('error', 'Произошла ошибка при добавлении.');

                $transaction->rollBack();

                throw $e;

                return $this->redirect(['index']);
            }

            return $this->render('upload', [
                'model' => $model,
            ]);

        } else {
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

}