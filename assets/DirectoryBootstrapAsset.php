<?php

namespace sizeg\directory\assets;

use yii\web\AssetBundle;

/**
 * DirectoryBootstrapAsset
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class DirectoryBootstrapAsset extends AssetBundle
{

    public $sourcePath = '@vendor/sizeg/yii2-directory/assets/source';
    public $js = [
        'bootstrap.directorymodal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
