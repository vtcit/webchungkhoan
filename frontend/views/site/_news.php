

    <!-- ======= News Section ======= -->
    <?php
        use common\models\Post;
        use common\models\Category;
        $posts = Post::find()->limit(6)->all();
    ?>
    <section id="news" class="news">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Tin tức</h2>
        </div>
        <div class="content">
            <?= $this->render('/post/block/block-4-3', ['posts' => $posts]) ?>
        </div>

      </div>
    </section><!-- End Portfolio Section -->