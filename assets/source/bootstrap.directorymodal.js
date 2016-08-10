/**
 * Use for update bootstrap modal content.
 * 
 * Create modal widget this way:
 * ```php
 * echo \yii\bootstrap\Modal::widget([
 *     'id' => 'directory-edit-modal',
 *     'header' => '<h3>&nbsp;</h3>',
 *     'clientOptions' => false,
 * ]);
 * ```
 * 
 * @author Dmitry Demin <sizemail@gmail.com>
 */

(function($){
    $.extend($.fn.modal.Constructor.prototype, {
        html: function (options) {
            if (options.title) {
                this.$element.find('.modal-header h3').html(options.title);
            }
            if (options.body) {
                this.$element.find('.modal-body').html(options.body);
            }
        }
    });
})(jQuery);
