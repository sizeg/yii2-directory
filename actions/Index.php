<?php

namespace sizeg\directory\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * Index
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class Index extends Action
{

    /**
     * @var string name of the view, which should be rendered
     */
    public $view = 'index';

    /**
     * @var callable should return the new service search model instance.
     */
    public $searchModel;

    /**
     * @var null|callable should return the data provider interface instance.
     */
    public $prepareDataProvider;

    /**
     * Creates a new search model instance
     * @return type
     * @throws InvalidConfigException
     */
    public function searchModel()
    {
        if ($this->searchModel !== null) {
            return call_user_func($this->searchModel, $this);
        } else {
            throw new InvalidConfigException('Either "' . get_class($this) . '::searchModel" must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $searchModel = $this->searchModel();
        $dataProvider = $this->prepareDataProvider($searchModel);

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new data provider interface instance
     * @param Model $searchModel
     * @return \yii\data\DataProviderInterface data provider instance
     */
    public function prepareDataProvider($searchModel)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $searchModel, $this);
        }

        return $searchModel->search(Yii::$app->request->getQueryParams());
    }
}
