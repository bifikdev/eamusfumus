<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

final class SiteController extends Controller
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    final public function actionError():array
    {
        $exception = Yii::$app->getErrorHandler()->exception;
        return [
            'code' => $exception->statusCode,
            'message' => $exception->getMessage(),
        ];
    }
}
