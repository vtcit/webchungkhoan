<!-- start block-2 -->
<div class="block-area block-2 mb-2 clearfix">
    <h3 class="h5 h-title">
        <a href="#"><span>Advisory</span></a>
    </h3>
    <?php for($i=0;$i<5;$i++) { ?>
        <article class="mb-4">
            <div class="row">
                <div class="col-3 col-md-4 pr-2 pr-md-0">
                    <div class="image-wrapper">
                        <a href="#" class="thumb resize">
                            <img class="img-fluid lazy" src="https://uploadbeta.com/api/pictures/random/?cached&key=BingEverydayWallpaperPicture&t=<?= $i+1 ?>" alt="Image description">
                        </a>
                    </div>
                </div>
                <!-- title & date -->
                <div class="col-9 col-md-8">
                    <h3 class="h4 post-title">
                        <a href="#">Nissan's sports car strategy rests on the stable genius of GT-R</a>
                    </h3>
                    <!-- author, date and comments -->
                    <div class="mb-2 text-muted small">
                        <span class="d-none d-sm-inline mr-1">
                            <a class="font-weight-bold" href="#">John Doe</a>
                        </span>
                        <time datetime="2019-10-22">Oct 22, 2019</time>
                        <span title="9 comment" class="float-right">
                            <span class="fa fa-comments" aria-hidden="true"></span> 9
                        </span>
                    </div>
                    <div class="post-intro">Kilmarnock miss chance to close on Celtic as they lose at home. Nissan's sports car strategy rests on the stable genius of GT-R</div>
                </div>
            </div>
        </article>
    <?php } ?>
</div> <!-- block-2 -->