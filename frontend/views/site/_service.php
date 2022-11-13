<?php
use yii\helpers\Url; 
?>
    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Gói dịch vụ</h2>
          <p>Hãy bắt đầu lựa chọn và đăng ký gói dịch vụ theo thời gian. Bạn sẽ được quyền xem các bảng khuyến nghị và lịch sử khuyến nghị.</p>
        </div>

        <div class="row text-center">
          <div class="col-xl-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4><a href="">Gói Starter</a></h4>
              <h5>7 ngày</h5>
              <p>Gói Start cho người mới bắt đầu. Bạn sẽ có 7 ngày để sử dụng dịch vụ.</p>
              <div class="btns mt-4"><a href="<?= Url::toRoute('user/signup') ?>" class="btn btn-info d-block">Đăng ký</a></div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4><a href="">Gói Professional</a></h4>
              <h5>30 ngày</h5>
              <p>Gói Professional dành cho các chuyên gia. Có 30 ngày sử dụng dịch vụ.</p>
              <div class="btns mt-4"><a href="<?= Url::toRoute('user/signup') ?>" class="btn btn-info d-block">Đăng ký</a></div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-tachometer"></i></div>
              <h4><a href="">Gói Pro 1</a></h4>
              <h5>60 ngày</h5>
              <p>Gói Pro 1 dành cho các chuyên gia.  60 ngày sử dụng dịch vụ.</p>
              <div class="btns mt-4"><a href="<?= Url::toRoute('user/signup') ?>" class="btn btn-info d-block">Đăng ký</a></div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="400">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-layer"></i></div>
              <h4><a href="">Gói Pro 2</a></h4>
              <h5>90 ngày</h5>
              <p>Gói Pro 1 dành cho các chuyên gia.  90 ngày sử dụng dịch vụ.</p>
              <div class="btns mt-4"><a href="<?= Url::toRoute('user/signup') ?>" class="btn btn-info d-block">Đăng ký</a></div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Services Section -->