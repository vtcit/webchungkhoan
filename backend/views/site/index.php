<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Dashboard Backend';
?>
<div class="site-index mt-5">
    <h1 class="page-header">Truy cập nhanh</h1>
    <div class="row">
        <div class="col-6 col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Trang</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?= Url::to(['page/index']) ?>">Tât cả các trang</a></li>
                    <li class="list-group-item"><a href="<?= Url::to(['page/page/create']) ?>">Thêm trang mới</a></li>
                </ul>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Dịch vụ</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?= Url::to(['page/service']) ?>">Tât cả các dịch vụ</a></li>
                    <li class="list-group-item"><a href="<?= Url::to(['page/service/create']) ?>">Thêm dịch vụ mới</a></li>
                </ul>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Danh mục bài viết</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?= Url::to(['category/index']) ?>">Tât cả các danh mục</a></li>
                    <li class="list-group-item"><a href="<?= Url::to(['category/category/create']) ?>">Thêm danh mục mới</a></li>
                </ul>
            </div>
        </div>
        <div class="col-6 col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bài viết</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><a href="<?= Url::to(['post/index']) ?>">Tât cả các bài viết</a></li>
                    <li class="list-group-item"><a href="<?= Url::to(['post/post/create']) ?>">Thêm bài viết mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
