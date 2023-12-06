<?php/** @var yii\web\View $this *//** @var $model common\modules\blog\models\Articles *//** @var $category frontend\controllers\SiteController *//** @var $community frontend\controllers\SiteController */use common\components\Gadget;use common\components\Jdf;use common\modules\blog\models\Articles;$url = Yii::$app->urlManager;$this->registerCssFile('@web/css/blogs.css');//echo '<pre>';//print_r($model);//exit();?><div class="container">    <div aria-label="Breadcrumb" class="breadcrumb d-block mx-auto my-5">        <ol>            <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">مهساآنلاین</a></li>            <?php if ($category == 0) { ?>                <li><a aria-current="page">مجله</a></li>            <?php }else { ?>                <li><a href="<?= Yii::$app->urlManager->createUrl(['/site/blogs']) ?>">مقالات</a></li>                <li><a aria-current="page"><?= $model[0]['title'] ?></a></li>            <?php } ?>        </ol>    </div></div><div class="container">    <?php if ($category==0){?>        <div class="d-flex justify-content-center align-items-center">            <h1 class="d-inline mx-2">مقالات</h1>            <span>(تعداد: <?= count($model)?>)</span>        </div>    <?php }else{?>            <div class="d-flex justify-content-center align-items-center">                <h1 class="d-inline mx-2"><?= $model[0]['title']?></h1>                <span>(تعداد مقالات: <?= count($model[0]['articles'])?>)</span>            </div>    <?php }?>    <?php    if ($category == 0){?>            <div class="row">                <div class="container px-3">                    <?php                    foreach ($model as $article) {                        $i=0;                        foreach ($community as $com){                            if ($com['parent_id'] == $article['id']){                                $i++;                            }}                            ?>                        <div class="row blog-card">                            <div class="header p-0">                                <img class="w-100" src="<?= Gadget::showFile($article['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $article['alt'] ?>">                            </div>                            <div class="col-8 mb-5"><h3 class="subject"><a href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>"><?= $article['title'] ?></a></h3></div>                            <div class="col-12 col-md-4 col-sm-12 align-items-center justify-content-md-end d-flex">                                <p class="m-0"><?=$i?><i class="fa fa-comments ms-3 me-2"></i></p>----                                <p class="date m-0"><?= Jdf::jdate('H:i - Y/m/d', $article['modify_date']) ?></p>                            </div>                            <div class="info my-4"><?= $article['introduction'] ?> </div>                            <a class="blog-link" href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>">ادامه مطلب</a>                        </div>    <?php }?>                </div>            </div>    <?php }else{?>        <div class="row">            <div class="container px-3">                <?php foreach ($model[0]['articles'] as $article) {                $i=0;                foreach ($community as $com){                    if ($com['parent_id'] == $article['id']){                        $i++;                    }}                    ?>                    <div class="row blog-card">                        <div class="header p-0">                            <img class="w-100" src="<?= Gadget::showFile($article['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $article['alt'] ?>">                        </div>                        <div class="col-8"><h3 class="subject"><a href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>"><?= $article['title'] ?></a></h3></div>                        <div class="col-12 mt-3 col-md-4 col-sm-12 align-items-center justify-content-md-end d-flex">                            <p class="m-0"><?=$i?><i class="fa fa-comments ms-3 me-2"></i></p>----                            <p class="date m-0"><?= Jdf::jdate('H:i - Y/m/d', $article['modify_date']) ?></p>                        </div>                        <div class="info my-4"><?= $article['introduction'] ?></div>                        <a class="blog-link" href="<?= $url->createUrl(['/site/article-view', 'id' => $article['id']]) ?>">ادامه مطلب</a>                    </div>                <?php } ?>            </div>        </div>    <?php }?></div>