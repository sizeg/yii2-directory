<?php

namespace sizeg\directory\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * DirectoryEditForm
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class DirectoryEditForm extends Widget
{

    /**
     * @var string modal id
     */
    public $modalId;
    
    /**
     * @var string form id
     */
    public $formId;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->formId)) {
            throw new \yii\base\InvalidConfigException('Either `formId` must be set.');
        }
        parent::init();
    }

    /**
     * @return string modal id
     */
    public function getModalId()
    {
        if (empty($this->modalId)) {
            $this->modalId = $this->getId() . '-modal';
        }

        return $this->modalId;
    }

    /**
     * Create link
     * @param string $text Link text
     * @param mixed $url URL
     * @return string link
     */
    public function createLink($text, $url)
    {
        return Html::a($text, '#', [
            'class' => 'btn btn-success',
            'data-url' => $url,
            'data-toggle' => 'modal',
            'data-target' => '#' . $this->getModalId(),
            'data-action' => 'create',
        ]);
    }

    /**
     * Update link (@see yii\grid\ActionColumn::buttons) for details
     * @param string $url
     * @param Model $model
     * @param string $key
     * @return string 
     */
    public function updateLink($url, $model, $key)
    {
        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', [
            'title' => Yii::t('yii', 'Update ajax'),
            'aria-label' => Yii::t('yii', 'Update'),
            'data-toggle' => 'modal',
            'data-target' => '#' . $this->getModalId(),
            'data-action' => 'update',
            'data-id' => $key,
            'data-url' => $url,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Modal::widget([
            'id' => $this->getModalId(),
            'header' => '<h3>&nbsp;</h3>',
            'clientOptions' => false,
        ]);

        $this->getView()->registerJs("
            $('#" . $this->getModalId() . "').on('show.bs.modal', function(e){
                var \$modal = $(this),
                    method = e.relatedTarget.dataset.verb || 'get',
                    url = e.relatedTarget.dataset.url;

                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'json',
                }).done(function(data){
                    \$modal.modal('html', {title: data.title, body: data.content});
                }).fail(function(){
                    \$modal.modal('html', {
                        title: 'Error',
                        body: 'Error loading data from server'
                    });
                    console.log('Saving failed: ', arguments);
                });
            });

            $('#" . $this->getModalId() . "').on('hidden.bs.modal', function(e){
                $(this).modal('html', {title: '&nbsp;', body: ''});
            });
            
            $(document).on('ajaxSubmitDone', '#" . $this->formId . "', function(e){
                setTimeout(function(){
                    $('#" . $this->getModalId() . "').modal('hide');
                }, 1500);
            });
        ");
    }
}
