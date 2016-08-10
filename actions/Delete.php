<?php

namespace sizeg\directory\actions;

use sizeg\directory\base\ModelNotFoundException;
use sizeg\directory\base\DirectoryServiceInterface;
use Exception;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;

/**
 * Delete
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class Delete extends Action
{

    /**
     * @var callable should return the new service instance.
     */
    public $service;

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
     * Success result
     * @return type
     */
    protected static function responseSuccess()
    {
        return [
            'title' => 'Delete',
            'content' => 'Item was successfully deleted.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $service = $this->service();

        try {
            $service->deleteById($id);
            return static::responseSuccess();
        } catch (ModelNotFoundException $e) {
            return static::responseSuccess();
        } catch (Exception $e) {
            Yii::$app->response->setStatusCode(400);
            return null;
        }
    }
}
