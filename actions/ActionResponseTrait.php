<?php

namespace sizeg\directory\actions;

use ReflectionClass;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

/**
 * ActionResponseTrait
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
trait ActionResponseTrait
{

    /**
     * Process response on post request
     * @param ActiveRecord $model
     * @return []|null array if model has errors or null
     */
    protected function responseOnPostRequest($model, $service)
    {
        $reflection = new ReflectionClass($model);
        $modelId = Inflector::camel2id($reflection->getShortName());
        $request = Yii::$app->request;

        // This is not validation or save?
        if ($request->post('ajax') !== $modelId && $request->post('ajax-submit') !== $modelId) {
            Yii::$app->response->setStatusCode(400);
            return null;
        } elseif ($model->load($request->post())) {
            $valid = ActiveForm::validate($model);
            // Validation request or errors while saving model?
            if ($request->post('ajax') == $modelId || $valid !== []) {
                return $valid;
            } else {
                try {
                    if (empty($model->id)) {
                        $service->createFromForm($model);
                    } else {
                        $service->updateFromForm($model);
                    }
                    Yii::$app->response->setStatusCode(204);
                    return null;
                } catch (Exception $e) {
                    Yii::$app->response->setStatusCode(400);
                    return null;
                }
            }
        }
    }
}
