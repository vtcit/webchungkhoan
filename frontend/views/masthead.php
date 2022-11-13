<?php
    $bgcolor = (isset($bgcolor)? $bgcolor : '#17a2b8');
    $background = isset($background)? $background : Yii::getAlias('@web/images/overlay.svg');
    $wavcolor = (isset($wavcolor)? $wavcolor: '#ffffff');
?>
<div<?php if(isset($id)) echo ' id="'.$id.'"'; ?> class="masthead py-5" style="position: relative; background-color: <?= $bgcolor ?>; background-image: url(<?= $background ?>), linear-gradient(45deg, <?= $bgcolor ?> 0%, <?= frontend\helpers\Functions::adjustBrightness($bgcolor, -15) ?> 100%); background-size: cover;">
    <div class="overlay"></div>
    <div class="container pb-5">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0 masthead-left">
                <?php if(isset($this->params['breadcrumbs']))
                    echo yii\bootstrap4\Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'],
                        'navOptions' => ['class' => 'text-light mt-n4',  'data-aos' => 'fade-up'],
                    ]);
                ?>
                <header class="entry-header">
                    <h1 class="text-center text-md-left text-light" data-aos="fade-up"><?= $heading ?></h1>
                    <?php if(isset($description) && $description) echo '<div class="lead text-light" data-aos="fade-up" data-aos-delay="200">'.$description.'</div>' ?>
                    <?php if(isset($buttons) && $buttons) { ?>
                        <div class="btns text-center text-md-left my-1" data-aos="fade-up" data-aos-delay="400">
                            <?php if(is_array($buttons)) {
                                foreach($buttons as $btn) { echo $btn; }
                            } else {
                                echo $buttons;
                            } ?>
                        </div>
                    <?php } ?>
                </header><!-- .entry-header -->
            </div>
            <div class="col-md-4 masthead-right" data-aos="zoom-in" data-aos-delay="400">
                <?php if(isset($card) && $card !== false && $card !== null) {
                    echo $card;
                } ?>
            </div>
        </div>
    </div>
    <?= $this->render('/wav-svg', ['color' => $wavcolor]) ?>
</div>