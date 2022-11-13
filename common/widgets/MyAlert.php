<?php
namespace common\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * Changed by Tuanict: join flash type in one Bootstrap Alert
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Tuanict <sam@rmcreative.ru>
 */
class MyAlert extends \yii\bootstrap\Widget
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
                echo \yii\bootstrap\Alert::widget([
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
