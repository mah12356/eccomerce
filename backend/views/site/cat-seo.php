<?php
/* @var $cat frontend\controllers\SiteController*/

foreach ($cat as $item){?>
    <div class="d-flex align-items-center justify-content-between border border-3">
        <p><?= $item['title'] ?></p>
        <a class="btn btn-dark" href="/admin/main/meta-tags/index?belong=category&parent_id=<?= $item['id']?>">مدیریت سئو</a>
    </div>
<?php }?>
<div class="d-flex align-items-center justify-content-between border border-3">
    <p>همه دسته بندی ها</p>
    <a class="btn btn-dark" href="/admin/main/meta-tags/index?belong=category&parent_id=0">مدیریت سئو</a>
</div>