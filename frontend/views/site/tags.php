<?php
/** @var $tag common\modules\main\models\Category */
/** @var $model common\modules\blog\models\Articles */
/** @var $posts common\modules\blog\models\Posts */

use common\components\Gadget;
use common\components\Jdf;
use common\modules\blog\models\Articles;
use common\modules\blog\models\Gallery;
use common\modules\blog\models\Posts;

$url = Yii::$app->urlManager;

$this->registerCssFile('@web/css/blogs.css');
?>
<div class="container">
    <div aria-label="Breadcrumb" class="breadcrumb d-block mx-auto my-5">
        <ol>
            <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">مهساآنلاین</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/blogs']) ?>">مقالات</a></li>
            <li><a aria-current="page"><?= $tag['title'] ?></a></li>
        </ol>
    </div>
</div>

<div class="container">
    <div class="row">
        <h2 class="title"><a href="<?= $url->createUrl(['/site/tags', 'id' => $tag['id']]) ?>"><?= $tag['title'] ?></a></h2>
        <div class="container">
            <?php foreach ($model as $article) { ?>
                <div class="row blog-card">
                    <div class="header p-0">
                        <img src="<?= Gadget::showFile($article['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $article['alt'] ?>">
                    </div>
                    <div class="col-8"><h3 class="subject"><a href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>"><?= $article['title'] ?></a></h3></div>
                    <div class="col-4"><p class="date"><?= Jdf::jdate('Y/m/d', $article['modify_date']) ?></p></div>
                    <p class="info my-4"><?= $article['introduction'] ?></p>
                    <a class="blog-link" href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>">ادامه مطلب</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container mt-5 pt-5">
    <div class="row blog-card tag-card">
        <article class="body">
            <?php foreach ($posts as $post) { ?>
                <!-- POST TITLE -->
                <section class="mt-5" id="section-<?= $post['id'] ?>"><?= $post['title'] ?></section>
                <!-- POST BANNER -->
                <?php if (Gadget::fileExist($post['banner'], Posts::UPLOAD_PATH)) {
                    $extension = explode('.', $post['banner']);
                    if ($extension[1] == 'mp4') {
                        ?>
                        <video class="banner" autoplay loop muted>
                            <source src="<?= Gadget::showFile($post['banner'], Posts::UPLOAD_PATH) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php } else { ?>
                        <img class="banner" src="<?= Gadget::showFile($post['banner'], Posts::UPLOAD_PATH) ?>"
                             alt="<?= $post['alt'] ?>">
                    <?php } ?>
                <?php } ?>
                <!-- POSTS -->
                <section class="mb-5">
                    <?= $post['text'] ?>
                    <?php if ($post['tip']) { ?>
                        <div class="alert alert-dark" role="alert"><?= $post['tip'] ?></div>
                    <?php } ?>
                </section>
                <div class="row mb-5">
                    <?php foreach ($post['images'] as $image) {
                        if ($image['type'] == Gallery::TYPE_CARD) { ?>
                            <div class="col-4">
                                <img class="w-100" src="<?= Gadget::showFile($image['image'], 'gallery') ?>" alt="<?= $image['alt'] ?>">
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </article>
    </div>
</div>