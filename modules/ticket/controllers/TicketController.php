<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.15
 * Time: 13:40
 */

namespace app\modules\ticket\controllers;


use app\models\Tickets;
use app\models\TicketsEvents;
use app\modules\user\controllers\UserMainController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class TicketController extends UserMainController{

    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view','create', 'close','open'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    protected function findTicket($id)
    {
        if (($model = Tickets::findOne(['id'=>$id, 'author_id'=>\Yii::$app->user->id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //список всех тикетов
    public function actionIndex()
    {
        $query = Tickets::find()->joinWith(['authorLastMsg'])->where(['author_id'=>\Yii::$app->user->id])->orderBy('date_last_msg DESC, id DESC');

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

    public function actionView($id){

        $model = $this->findTicket($id);

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
                $model->is_new_for_admin = 1;
                $model->save(false);
            }
        }

        return $this->render('view', ['model'=>$model, 'answers'=>$answers, 'answer_form'=>$answer_form]);
    }

    public function actionCreate(){

        $model = new Tickets();

        $model->prioritet = Tickets::PRIORITET_MIDDLE;

        if ($model->load(\Yii::$app->request->post()) && $model->save())
        {

            //обновление всех меток времени и запишим текст сообщения
            $event = new TicketsEvents();
            $event->ticket_id = $model->id;
            $event->msg = $model->question;
            $event->event_status = TicketsEvents::STATUS_OPEN;
            $event->created = time();
            $event->save();


            //отрпавим письмо админу о создании тикета

            \Yii::$app->mailer->compose(['html'=>'create_ticket'], ['model' => $model])
                ->setFrom([$model->author->email => 'Клиент системы'])
                ->setTo('we@moab.pro')
                ->setSubject('Создан новый тикет №'.$model->id)
                ->send();


            \Yii::$app->getSession()->setFlash('success', 'Успешно создали тикет');

            return $this->redirect(\Yii::$app->urlManager->createAbsoluteUrl('/ticket'));
        }

        return $this->render('create',[
            'model'=>$model
        ]);
    }

    public function actionClose($id)
    {
        if(\Yii::$app->request->isPost){

            $model = $this->findTicket($id);

            if($model->status!==Tickets::STATUS_CLOSE)
            {
                //throw new BadRequestHttpException('Bad request');
                $model->status = Tickets::STATUS_CLOSE;

                $model->date_last_msg = time();

                $model->author_id_last_msg = \Yii::$app->user->id;

                $model->save(false);
            }

            return $this->redirect(['index']);
        }else{
            throw new BadRequestHttpException('Bad request');
        }

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

                return $this->redirect(\Yii::$app->request->referrer);
            }else{
                throw new BadRequestHttpException('Bad request');
            }
        }
    }
}