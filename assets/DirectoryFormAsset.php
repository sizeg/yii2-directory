<?php

namespace sizeg\directory\assets;

use yii\web\AssetBundle;

/**
 * DirectoryFormAsset
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class DirectoryFormAsset extends AssetBundle
{

    public $sourcePath = '@vendor/sizeg/yii2-directory/assets/source';
    public $js = [
        'yii.directoryform.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
