<?php

namespace sizeg\directory\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * DirectoryDeleteForm
 *
 * @author Dmitry Demin <sizemail@gmail.com>
 */
class DirectoryDeleteForm extends Widget
{

    /**
     * @var array|string $action the form action URL. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @see method for specifying the HTTP method for this form.
     */
    public $action = '#';

    /**
     * @var array the HTML attributes (name-value pairs) for the form tag.
     * @see Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string the form submission method. This should be either 'post' or 'get'. Defaults to 'post'.
     */
    public $method = 'post';

    /**
     * @var string Modal id
     */
    public $modalId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->options['class'])) {
            $this->options['class'] = 'hidden';
        } elseif (!preg_match('#(^|\s)hidden($|\s)#', $this->options['class'])) {
            $this->options['class'] .= ' hidden';
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
     * @inheritdoc
     */
    public function run()
    {
        echo Modal::widget([
            'id' => $this->getModalId(),
            'header' => '<h3>&nbsp;</h3>',
            'clientOptions' => false,
        ]);

        echo Html::beginForm($this->action, $this->method, $this->options);
        echo Html::endForm();
        
        $this->getView()->registerJs("
            $('#" . $this->getId() . "').on('submit', function (e) {
                e.preventDefault();
                var \$form = $(this);
                $.ajax({
                    url: this.action,
                    method: 'DELETE',
                    dataType: 'json'
                }).done(function(data){
                    \$form.trigger('ajaxDeleteDone');
                }).fail(function(){
                    $('#" . $this->getModalId() . "').modal('html', {
                        title: 'Error',
                        body: 'Error loading data from server'
                    });
                    console.log('Deleting failed: ', arguments);
                });
            });
        ");
    }
    
    /**
     * Delete link (@see yii\grid\ActionColumn::buttons) for details
     * @param string $url
     * @param Model $model
     * @param string $key
     * @return string 
     */
    public function deleteLink($url, $model, $key)
    {
        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
            'title' => Yii::t('yii', 'Delete'),
            'aria-label' => Yii::t('yii', 'Delete'),
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-form' => $this->getId(),
            'data-id' => $key,
        ]);
    }
}
