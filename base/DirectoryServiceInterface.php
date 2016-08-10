<?php

namespace sizeg\directory\base;

/**
 * DirectoryServiceInterface
 * Interface to implements by directory layer services
 * 
 * @author Dmitry Demin <sizemail@gmail.com>
 */
interface DirectoryServiceInterface
{

    /**
     * Create new row from model form
     * @param \yii\base\Model $form form model
     * @return \yii\db\ActiveRecord
     */
    public function createFromForm($form);

    /**
     * Update row from model form
     * @param \yii\base\Model $form form model
     * @return \yii\db\ActiveRecord
     */
    public function updateFromForm($form);

    /**
     * Load row into form model
     * @param \yii\base\Model $form form model
     * @return \yii\base\Model
     */
    public function loadForm($form);
}
