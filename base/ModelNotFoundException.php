<?php

namespace sizeg\directory\base;

/**
 * ModelNotFoundException
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class ModelNotFoundException extends \yii\base\Exception
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Model not found';
    }
}
