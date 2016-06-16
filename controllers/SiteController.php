<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    private function generateCode($length = 4)
    {
        $code = '';
        $symbols = '0123456789abcdefghijklmnopqrstuvwxyz_-';
        for ($i = 0; $i < (int)$length; $i++) {
            $num = rand(1, strlen($symbols));
            $code .= substr($symbols, $num, 1);
        }
        return $code;
    }

    private function generateShortUrl($code) {
        return 'http://'.$_SERVER['SERVER_NAME'].'/'.$code;
    }

    public function actionShort()
    {
        $cache = Yii::$app->cache;
        $request = Yii::$app->request;
        $url = $request->post('url', '');
        if (empty($url)) {
            return '';
        }
        $code = $this->generateCode();
        while(!empty($cache->get($code))) {
            $code = $this->generateCode();
        }
        $cache->set($code, $url, Yii::$app->params['lifetime'] );
        return 'Ваша ссылка: <a href="'.$this->generateShortUrl($code).'">'.$this->generateShortUrl($code).'</a>';
    }

    public function actionView($code = '') {
        $cache = Yii::$app->cache;
        if(empty($code)) {
            throw new \yii\web\NotFoundHttpException("Нет такого кода.");
        }
        return $this->redirect($cache->get($code),302);
    }
}
