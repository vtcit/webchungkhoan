<?php
use yii\helpers\Url; 
?>
    <!-- ======= Skills Section ======= -->
    <section id="skills" class="skills">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 d-flex align-items-center" data-aos="fade-right" data-aos-delay="100">
            <img src="assets/img/skills.png" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left" data-aos-delay="100">
            <h3>Khuyến nghị thị trường</h3>
            <p class="fst-italic">
            Bảng khuyến nghị thị trường sẽ đưa ra các đầu Mã chứng khoán trong ngày. Chúng tôi sẽ đánh giá, đưa ra tỷ trọng và quyết định Mua hay Bán.
            </p>

            <div class="skills-content">

              <div class="progress">
                <span class="skill">Số liệu <i class="val">100%</i></span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>

              <div class="progress">
                <span class="skill">Độ tin cậy <i class="val">90%</i></span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>

              <div class="progress">
                <span class="skill">Thành công <i class="val">75%</i></span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>

              <div class="progress">
                <span class="skill">Khác <i class="val">55%</i></span>
                <div class="progress-bar-wrap">
                  <div class="progress-bar" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="btns mt-5 text-center"><a href="<?= Url::toRoute('recommendation/index') ?>" class="btn btn-lg btn-warning px-4">Xem bảng khuyến nghị</a></div>
      </div>
    </section><!-- End Skills Section -->