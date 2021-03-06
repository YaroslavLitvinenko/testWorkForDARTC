<?php

namespace app\controllers;

use app\models\Feedback;
use app\models\FeedbackForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\imagine\Image;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'feedback-list'],
                'rules' => [
                    [
                        'actions' => ['logout', 'feedback-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $form = new FeedbackForm();

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($form);
        }

        if ($form->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('feedbackFormSubmit');
            $form->file = UploadedFile::getInstance($form, 'file');
            $img = null;
            $miniature = null;

            $model = $form->getModel();
            if ($form->upload()) {
                if ($form->file->extension !== 'txt') {
                    //Determine the Big Side for a Miniature
                    $img = Image::getImagine()->open("files/$model->file");
                    $miniature = $img->getSize()->getWidth() > $img->getSize()->getHeight() ? true : false;

                    $img = $model->file;
                }
            }
            $model->save();
            $form->sendMail(Yii::$app->params['adminEmail']);

            return $this->render('feedbackForm', [
                'img' => $img,
                'miniature' => $miniature
            ]);
        }

        return $this->render('feedbackForm', [
            'model' => $form
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->redirect(Yii::$app->urlManager->createUrl('site/feedback-list'));
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionFeedbackList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Feedback::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort' => ['attributes' => ['username','email', 'date']]
        ]);

        return $this->render('listFeedbackMassage', [
            'dataProvider' => $dataProvider
        ]);
    }
}
