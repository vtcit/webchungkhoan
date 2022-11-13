<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\LeoTinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

$availableCats = $model->getAvailableCats()->where(['parent_id' => null, 'type' => $this->params['type']->name])->andWhere(['<>', 'id', intval($model->id)])->all();
if($availableCats)
{
    $availableCats = \yii\helpers\ArrayHelper::map($availableCats , 'id', 'title');
}
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($availableCats, ['prompt'=>'- No Parent -']) ?>

    <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'description')->widget(LeoTinyMce::className()) ?>

    <?php if(isset($this->params['type']->attributes)) {
        foreach($this->params['type']->attributes as $k => $att_group) {
            $att_group = (object) $att_group;
    ?>
        <div id="att_group<?= $k ?>">
                <?php if($att_group->label) echo '<h4>'.$att_group->label.'</h4>'; ?>
            <?php foreach($att_group->items as $item) {
                $item = (object) $item;
                switch($item->type)
                {
                    case 'number':
                    case 'password':
                    case 'tel':
                    case 'email':
                        echo $form->field($model, "meta[$item->name]")->input($item->type)->label($item->label);
                        break;
                    case 'checkbox':
                        if(isset($model->meta[$item->name]))
                        {
                            $model->meta[$item->name] = json_decode($model->meta[$item->name]);
                        }
                        echo $form->field($model, "meta[$item->name]")->checkboxList($item->options)->label($item->label);
                        break;
                    case 'radio':
                        echo $form->field($model, "meta[$item->name]")->radioList($item->options)->label($item->label);
                        break;
                    case 'select':
                        echo $form->field($model, "meta[$item->name]")->dropDownList($item->options)->label($item->label);
                        break;
                    case 'multi-select':
                        if(isset($model->meta[$item->name]))
                        {
                            $model->meta[$item->name] = json_decode($model->meta[$item->name]);
                        }
                        echo $form->field($model, "meta[$item->name]")->listBox($item->options, ['multiple' => true])->label($item->label);
                        break;
                    case 'textarea':
                        echo $form->field($model, "meta[$item->name]")->textarea(['rows' => 5])->label($item->label);
                        break;
                    case 'editor':
                        echo $form->field($model, "meta[$item->name]")->widget(LeoTinyMce::className())->label($item->label);
                        break;
                    case 'media':
                        echo $form->field($model, "meta[$item->name]")->hiddenInput()->label($item->label)->hint('<div class="preview media_0 text-center text-ellipsis">Image</div> <br />'.Html::button('<i class="fas fa-plus"></i> '.Yii::t('app', 'Add Media'), ['class' => 'btn btn-sm btn-default']));
                        break;
                    default:
                        echo $form->field($model, "meta[$item->name]")->textInput()->label($item->label);
                        break;
                } // switch
            } //foreach ($att_group->items) ?>
        </div>
    <?php
        } //foreach($this->params['type']->attributes)
    } // if(isset($this->params['type']->attributes)) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
