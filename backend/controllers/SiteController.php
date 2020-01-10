<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\actions\GetSlug;
use backend\behaviors\ControllerHelper;
use backend\models\LoginForm;
use backend\models\PasswordForm;

/**
 * Class SiteController
 *
 * @mixin ControllerHelper
 *
 * @package backend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class SiteController extends Controller
{
    /**
     * @var string - action ошибок
     */
    protected $errorAction = 'yii\web\ErrorAction';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'access' => [ ], // доступ закрыт через конфиг /backend/config/main.php [as beforeRequest]
            ControllerHelper::class,
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (($action instanceof $this->errorAction)) {
            $this->layout = 'base';
        }

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => $this->errorAction,
            'get-slug' => GetSlug::class,
        ];
    }

    /**
     * Displays homepage.
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['/frontend/main']);
    }

    /**
     * Login action.
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack('/');
        }

        $this->layout = 'base';

        return $this->render('login', [
            'loginForm' => $loginForm,
        ]);
    }

    /**
     * Logout action.
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        Yii::$app->session->setFlash('success', 'Вы успешно вышли.');

        return $this->goHome();
    }

    /**
     * Настройки
     * - смена пароля
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionSettings()
    {
        $passwordForm = new PasswordForm(Yii::$app->user->getIdentity()->getId());

        if ($passwordForm->load(Yii::$app->request->post()) && $passwordForm->changePassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
            return $this->refresh();
        }

        return $this->render('settings', [
            'passwordForm' => $passwordForm,
        ]);
    }

    /**
     * Переадресация файлов на фронт
     * @param $url
     * @return \yii\web\Response
     */
    public function actionFiles($url)
    {
        return $this->redirect('@frontendWeb/' . $url);
    }
}
