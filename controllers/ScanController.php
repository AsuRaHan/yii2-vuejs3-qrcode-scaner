<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

class ScanController extends Controller
{
    public function behaviors()
    {
        return [
            'cors' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => ['*'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $request = Yii::$app->getRequest();
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;

        $content = $request->getBodyParam('content');
        if (empty($content)) {
            $response->statusCode = 400;
            return ['error' => 'Отсутствует содержимое сканированного QR-кода.'];
        }

        // Здесь можно добавить код для сохранения сканированного QR-кода в базу данных или файл.

        $response->statusCode = 201;
        return ['success' => true];
    }


}