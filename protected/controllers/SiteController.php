<?php

class SiteController extends CController
{
    public function actionHealth()
    {
        header('Content-Type: application/json; charset=UTF-8');

        try {
            Yii::app()
                ->db
                ->createCommand('SELECT 1')
                ->queryScalar();

            echo CJSON::encode([
                'status' => 'ok',
                'database' => 'ok',
            ]);
        } catch (Exception $exception) {
            http_response_code(503);

            echo CJSON::encode([
                'status' => 'error',
                'database' => 'unavailable',
            ]);
        }

        Yii::app()->end();
    }
}