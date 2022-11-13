<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $this->params['type']->label).' công nghệ Vustech';
$this->params['metatag']['url'] = Url::to(['page/index', 'type' => $type], true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = 'Chúng tôi cung cấp các dịch vụ giải pháp công nghệ để thúc đẩy phát triển trong kinh doanh và tăng hiệu quả hoạt động doanh nghiệp.';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'template' => "<li class=\"breadcrumb-item d-none\">{link}</li>\n"];

?>
<?= $this->render('/masthead', [
    'bgcolor' => '#17a2b8',
    'heading' => Html::encode($this->title),
    'description' => $this->params['metatag']['description'],
]) ?>
<div class="pages container py-5">
    <?= yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'page-container',
        ],
        'layout' => "\n<div class=\"row row-list page-list\">\n{items}\n</div>\n<div class=\"text-center\">{pager}</div>",
        'itemView' => '_item',
        'itemOptions' => [ 'class' => 'col-lg-4 col-md-4 col-sm-6 col-xs-12' ],
        //'pager' => ['options' => ['class' => 'text-center']]
    ]);
    ?>
</div>
