<?php
use yii\helpers\Url; 
?>
    <!-- ======= About Us Section ======= -->
    <section id="about" class="about section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Lịch sử khuyến nghị</h2>
        </div>
        <div class="row content">
          <div class="col-lg-6">
            <p>
              Chức năng xem lịch sử khuyến nghị giúp cho khách hàng thuận tiện tra cứu thông tin về mã cổ phiếu đang quan tâm.
              Khi khách hàng chọn một mã chứng khoán, nhập khoảng thời gian, hệ thống sẽ đưa ra các khuyến nghị mua bán.
            </p>
            <ul>
              <li><i class="ri-check-double-line"></i> Thuận tiện tra cứu thông tin</li>
              <li><i class="ri-check-double-line"></i> Tìm kiếm trong khoảng thời gian</li>
              <li><i class="ri-check-double-line"></i> Đưa ra các khuyến nghị mua bán</li>
            </ul>
            <div class="btns mt-3"><a href="<?= Url::toRoute('recommendation/history') ?>" class="btn btn-lg btn-info px-4">Xem lịch sử</a></div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0">
            <img src="assets/img/thong ke.png" alt="">
          </div>
        </div>

      </div>
    </section><!-- End About Us Section -->