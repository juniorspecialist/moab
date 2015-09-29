<?php

namespace app\modules\ticket\controllers;

use app\models\Tickets;
use app\models\TicketsEvents;
use app\models\TicketsSearch;
use app\modules\admin\controllers\BaseAdminController;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class AdminController extends BaseAdminController
{
    //список всех тикетов
    public function actionIndex()
    {


        //$query = Tickets::find()->joinWith(['author','authorLastMsg'])->orderBy('date_last_msg DESC, ');

        $searchModel = new TicketsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => false,
//            'pagination' => [
//                'pageSize' => 50,
//            ],
//        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionView($id){

        $model = $this->findTicket($id);

        if($model->is_new_for_admin==1)
        {
            $model->is_new_for_admin = 0;
            $model->update(false);
        }


        $answers = TicketsEvents::find()->joinWith(['user'])->where(['ticket_id'=>$model->id])->asArray()->all();

        $answer_form = new TicketsEvents();
        $answer_form->ticket_id = $model->id;
        $answer_form->event_status = TicketsEvents::STATUS_ADD_MSG;

        if(\Yii::$app->request->isAjax){
            if ($answer_form->load(\Yii::$app->request->post()) && $answer_form->save())
            {
                $answer_form->msg = '';

                //обновим данные в тикете - общие сведения
                $model->date_last_msg = time();
                $model->author_id_last_msg = \Yii::$app->user->id;
                $model->is_new_for_user = 1;
                $model->save(false);

                Yii::$app->mailer->compose(['html'=>'answer_ticket'], ['model' => $model])
                    ->setFrom(['we@moab.pro' => 'MOAB.Pro'])
                    ->setTo($model->author->email)
                    ->setSubject('Изменение в вашем тикете №'.$model->id)
                    ->send();
            }
        }

        return $this->render('view', ['model'=>$model, 'answers'=>$answers, 'answer_form'=>$answer_form]);
    }

    protected function findTicket($id){
        if (($model = Tickets::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * закрываем тикет
     */
    public function actionClose($id){

        $model = $this->findTicket($id);

        if($model->status==Tickets::STATUS_OPEN){

            $model->status = Tickets::STATUS_CLOSE;

            $model->date_last_msg = time();

            $model->author_id_last_msg = \Yii::$app->user->id;

            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionOpen($id)
    {
        if(\Yii::$app->request->isPost)
        {
            if(\Yii::$app->request->isPost){

                $model = $this->findTicket($id);

                if($model->status==Tickets::STATUS_CLOSE)
                {
                    //throw new BadRequestHttpException('Bad request');
                    $model->status = Tickets::STATUS_OPEN;

                    $model->date_last_msg = time();

                    $model->author_id_last_msg = \Yii::$app->user->id;

                    $model->save(false);

                    $event = new TicketsEvents();
                    $event->ticket_id = $model->id;
                    $event->msg = 'Открыли тикет';
                    $event->event_status = TicketsEvents::STATUS_OPEN;
                    $event->created = time();
                    $event->save();
                }

                //return $this->redirect(['index']);
                return $this->redirect(Yii::$app->request->referrer);
            }else{
                throw new BadRequestHttpException('Bad request');
            }
        }
    }
}
