<?php

namespace sizeg\directory\actions;

use sizeg\directory\base\DirectoryServiceInterface;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\web\Response;

/**
 * Create
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class Create extends Action
{

    use ActionResponseTrait;

    /**
     * @var string name of the view, which should be rendered
     */
    public $view = 'create_modal';

    /**
     * @var string prefix of the view's name
     */
    public $viewPrefix;

    /**
     * @var string model scenario
     */
    public $scenario;

    /**
     * @var callable should return the new model instance.
     */
    public $formModel;

    /**
     * @var callable should return the new service instance.
     */
    public $service;

    /**
     * Creates new model instance.
     * @return ActiveRecordInterface|Model new model instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function formModel()
    {
        if ($this->formModel !== null) {
            return call_user_func($this->formModel, $this);
        } else {
            throw new InvalidConfigException('Either "' . get_class($this) . '::formModel" must be set.');
        }
    }

    /**
     * Creates new service instance.
     * @return DirectoryServiceInterface new service instance.
     * @throws InvalidConfigException on invalid configuration.
     */
    public function service()
    {
        if ($this->service !== null) {
            return call_user_func($this->service, $this);
        } else {
            throw new InvalidConfigException('Either "' . get_class($this) . '::service" must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->formModel();
        $model->scenario = $this->scenario;

        if (Yii::$app->request->getIsPost()) {
            $service = $this->service();
            return $this->responseOnPostRequest($model, $service);
        }

        Yii::$app->assetManager->bundles['yii'] = false;

        $content = $this->controller->renderAjax($this->view, [
            'model' => $model
        ]);

        return [
            'title' => $this->controller->getView()->title,
            'content' => $content,
        ];
    }
}
