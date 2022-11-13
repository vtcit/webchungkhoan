<?php
namespace frontend\widgets;

use Yii;

/**
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * Changed by Tuanict: join flash type in one Bootstrap4 Alert
 *
 */
class Alert extends \yii\bootstrap4\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach($this->alertTypes as $type=>$typeClass)
        {
            if($flash = $session->getFlash($type))
            {
                $body = '';
                if(!is_array($flash))
                {
                    $flash = [$flash];
                }

                foreach($flash as $k=>$msg)
                {
                    $body .= '<div class="'.$type.'-'.$k.'">'.$msg.'</div>';
                }
                echo \yii\bootstrap4\Alert::widget([
                    'body' => $body,
                    'closeButton' => $this->closeButton,
                    'options' => array_merge($this->options, [
                        'id' => $this->getId().'-'.$typeClass,
                        'class' => $typeClass.$appendClass,
                    ]),
                ]);
                //$session->removeFlash($type);
            }
        }
        $session->removeAllFlashes();
    }
}
