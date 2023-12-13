<?php

/** @var yii\web\View $this */

/** @var string $content */

use backend\assets\AppAsset;
use common\components\Gadget;
use common\models\Booking;
use common\models\Community;
use common\models\Faq;
use common\models\Tickets;
use common\models\User;
use common\modules\blog\models\Posts;
use common\modules\blog\models\Slider;
use common\modules\main\models\Category;
use common\modules\main\models\Config;
use common\modules\main\models\MetaTags;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);

$url = Yii::$app->urlManager;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body dir="rtl">
    <header class="header">
        <section class="sidebar-header bg-gray">
            <section class="d-flex justify-content-between flex-md-row-reverse px-2">
                <span id="sidebar-toggle-show" class="d-inline d-md-none pointer"><i
                        class="fas fa-toggle-off"></i></span>
                <span id="sidebar-toggle-hide" class="d-none d-md-inline pointer"><i
                        class="fas fa-toggle-on"></i></span>
                <a href="<?= $url->createUrl(['/site/index']) ?>">
                    <img class="logo" src="<?= Gadget::showFile(Config::getKeyContent(Config::KEY_LOGO), 'config') ?>"
                         alt="logo">
                </a>
                <span class="d-md-none" id="menu-toggle"><i class="fas fa-ellipsis-h"></i></span>
            </section>
        </section>
        <section class="body-header" id="body-header">
            <section class="d-flex justify-content-between">
                <section>
                    <span class="mx-4">
                        <span id="search-area" class="search-area d-none">
                            <i id="search-area-hide" class="fas fa-times pointer"></i>
                            <label class="sr-only" for="search-input">search</label>
                            <input id="search-input" type="text" class="search-input">
                            <i class="fas fa-search pointer"></i>
                        </span>
                        <i id="search-toggle" class="fas fa-search p-1 d-none d-md-inline pointer"></i>
                    </span>
                    <span id="full-screen" class="pointer p-1 d-none d-md-inline mr-5">
                        <i id="screen-compress" class="fas fa-compress d-none"></i>
                        <i id="screen-expand" class="fas fa-expand"></i>
                    </span>
                </section>
                <section>
                    <div class="ms-3 ms-md-5 position-relative">
                        <span id="header-profile-toggle" class="pointer">
                            <i class="fas fa-angle-down"></i>
                            <span
                                class="header-username"><?= Yii::$app->user->identity->name ?> <?= Yii::$app->user->identity->lastname ?></span>
                            <img class="header-avatar" src="/upload/img/admin-avatar.png" alt="">
                        </span>
                        <section id="header-profile" class="header-profile rounded">
                            <section class="list-group rounded">
                                <a href="<?= $url->createUrl(['/main/config/index']) ?>"
                                   class="list-group-item list-group-item-action header-profile-link"><i
                                        class="fas fa-cog"></i>تنظیمات</a>
                                <?= Html::a('<i class="fas fa-sign-out-alt"></i>خروج', ['site/logout'], ['data-method' => 'post', 'class' => 'list-group-item list-group-item-action header-profile-link']) ?>
                            </section>
                        </section>
                    </div>
                </section>
            </section>
        </section>
    </header>
    <section class="body-container">
        <aside id="sidebar" class="sidebar">
            <section class="sidebar-container">
                <section class="sidebar-wrapper">

                    <section class="sidebar-part-title">منو</section>
                    <hr>
                    <a href="<?= $url->createUrl(['/main/config/index']) ?>" class="sidebar-link"><i class="fas fa-cog"></i><span>تنظیمات</span></a>
                    <a href="<?= $url->createUrl(['/main/result/create']) ?>" class="sidebar-link"><i class="fa fa-list-alt" aria-hidden="true"></i>
                        <span>ساخت نتیجه پکیج</span></a>
                    <a href="<?= $url->createUrl(['/main/demos/']) ?>" class="sidebar-link"><i class="fa fa-list-alt" aria-hidden="true"></i>
                        <span>دموی کلاس</span></a>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-home icon"></i><span>خانه</span><i class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/main/meta-tags/index', 'parent_id' => 0, 'belong' => MetaTags::BELONG_HOME]) ?>">متا تگ ها</a>
                            <a href="<?= $url->createUrl(['/blog/posts/index', 'page_id' => 0, 'belong' => Posts::BELONG_HOME]) ?>">متن ها</a>
                            <a href="<?= $url->createUrl(['/blog/gallery/list']) ?>">پیشرفت شاگردان</a>
                            <a href="<?= $url->createUrl(['/faq/index', 'belong' => Faq::BELONG_HOME]) ?>">سوالات متداول</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-address-book icon"></i><span>درباره ما</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/main/meta-tags/index', 'parent_id' => -1, 'belong' => MetaTags::BELONG_PAGE]) ?>">متا تگ ها</a>
                            <a href="<?= $url->createUrl(['/blog/posts/index', 'page_id' => 0, 'belong' => Posts::BELONG_ABOUT]) ?>">متن ها</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-comment-dots icon"></i><span>نظرات</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/comments/create']) ?>">ثبت نظر</a>
                            <a href="<?= $url->createUrl(['/comments/index']) ?>">لیست نظرات</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-bell icon"></i><span>اعلانات</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/hints/create']) ?>">ثبت اعلان</a>
                            <a href="<?= $url->createUrl(['/hints/index']) ?>">لیست اعلانات</a>
                        </section>
                    </section>
                    <a href="<?= $url->createUrl(['/analyze/index']) ?>" class="sidebar-link"><i class="fas fa-heartbeat"></i><span>آنالیز بدنی</span></a>
                    <a href="<?= $url->createUrl(['/advertise/index']) ?>" class="sidebar-link"><i class="fas fa-envelope icon"></i><span>تبلیغات</span></a>
                    <a href="<?= $url->createUrl(['/site/finance']) ?>" class="sidebar-link"><i class="fas fa-database"></i><span>لیست شاگردان</span></a>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-fax icon"></i><span>تماس تلفنی</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/booking/create']) ?>">ثبت گفتگو</a>
                            <a href="<?= $url->createUrl(['/booking/index']) ?>">تماس های در صف بررسی</a>
                            <a href="<?= $url->createUrl(['/booking/index', 'status' => Booking::STATUS_CHECKED]) ?>">تماس های بررسی شده</a>
                        </section>
                    </section>
                    <a href="<?= $url->createUrl(['/site/users-list']) ?>" class="sidebar-link"><i class="fas fa-database"></i><span>ثبت نامی‌های جدید</span></a>

                    <section class="sidebar-part-title">دسترسی ها</section>
                    <a href="<?= $url->createUrl(['/main/config/modify', 'key' => Config::KEY_ANALYZE_FORM]) ?>" class="sidebar-link">
                        <i class="fas fa-cog"></i>
                        <span>فرم آنالیز</span>
                        <?php if (Config::getKeyContent(Config::KEY_ANALYZE_FORM) == 1) { ?>
                            <span class="badge text-bg-success me-1">فعال</span>
                        <?php }else { ?>
                            <span class="badge text-bg-danger me-1">غیرفعال</span>
                        <?php } ?>
                    </a>
                    <a href="<?= $url->createUrl(['/main/config/modify', 'key' => Config::KEY_ANALYZE_TICKET]) ?>" class="sidebar-link">
                        <i class="fas fa-cog"></i>
                        <span>تیکت‌های آنالیز</span>
                        <?php if (Config::getKeyContent(Config::KEY_ANALYZE_TICKET) == 1) { ?>
                            <span class="badge text-bg-success me-1">فعال</span>
                        <?php }else { ?>
                            <span class="badge text-bg-danger me-1">غیرفعال</span>
                        <?php } ?>
                    </a>
                    <a href="<?= $url->createUrl(['/main/config/modify', 'key' => Config::KEY_DIET_TICKET]) ?>" class="sidebar-link">
                        <i class="fas fa-cog"></i>
                        <span>تیکت‌های تغذیه</span>
                        <?php if (Config::getKeyContent(Config::KEY_DIET_TICKET) == 1) { ?>
                            <span class="badge text-bg-success me-1">فعال</span>
                        <?php }else { ?>
                            <span class="badge text-bg-danger me-1">غیرفعال</span>
                        <?php } ?>
                    </a>

                    <section class="sidebar-part-title">پیام رسان</section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-envelope icon"></i><span>تیکت‌های تغذیه</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_DIET]) ?>">پیام های جدید</a>
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_DIET, 'status' => Tickets::STATUS_CHECKED_ALL_ADMIN]) ?>">خوانده شده</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-envelope icon"></i><span>تیکت‌های آنالیز</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_ANALYZE]) ?>">پیام های جدید</a>
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_ANALYZE, 'status' => Tickets::STATUS_CHECKED_ALL_ADMIN]) ?>">خوانده شده</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-envelope icon"></i><span>تیکت‌های مهساآنلاین</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_COACH]) ?>">پیام های جدید</a>
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_COACH, 'status' => Tickets::STATUS_CHECKED_ALL_ADMIN]) ?>">خوانده شده</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-envelope icon"></i><span>تیکت‌های پشتیبانی</span><i
                                    class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_SUPPORT]) ?>">پیام های جدید</a>
                            <a href="<?= $url->createUrl(['/tickets/index', 'type' => Tickets::TYPE_SUPPORT, 'status' => Tickets::STATUS_CHECKED_ALL_ADMIN]) ?>">خوانده شده</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-address-book icon"></i><span>مدیریت کانال‌ها</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/channels/create']) ?>">ساخت کانال جدید</a>
                            <a href="<?= $url->createUrl(['/channels/index']) ?>">لیست کانال‌ها</a>
                        </section>
                    </section>

                    <section class="sidebar-part-title">کاربران</section>
                    <a href="<?= $url->createUrl(['/diet/specify']) ?>" class="sidebar-link"><i class="fas fa-upload"></i><span>رژیم‌های ارسال نشده</span></a>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-users icon"></i><span>مدیریت کاربران</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/site/users', 'role' => User::ROLE_ADMIN]) ?>">ادمین</a>
                            <a href="<?= $url->createUrl(['/site/users', 'role' => User::ROLE_AUTHOR]) ?>">مولف</a>
                            <a href="<?= $url->createUrl(['/site/users', 'role' => User::ROLE_COACH]) ?>">مربی</a>
                            <a href="<?= $url->createUrl(['/site/users', 'role' => User::ROLE_USER]) ?>">شاگرد</a>
                        </section>
                    </section>
                    <a href="<?= $url->createUrl(['/site/find-user']) ?>" class="sidebar-link"><i class="fas fa-users"></i><span>ثبت نام پکیج</span></a>

                    <section class="sidebar-part-title">وبلاگ</section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-server icon"></i><span>دسته‌بندی مقالات</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/main/category/create', 'belong' => Category::BELONG_BLOG]) ?>">ساخت
                                دسته جدید</a>
                            <a href="<?= $url->createUrl(['/main/category/index', 'belong' => Category::BELONG_BLOG]) ?>">لیست
                                دسته‌ها</a>
                            <a href="<?= $url->createUrl(['/site/cat-seo'])?>">مدیریت سئو دسته بندی ها</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-tags icon"></i><span>مدیریت برچسب ها</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/main/category/create', 'belong' => Category::BELONG_TAG]) ?>">ساخت
                                برچسب جدید</a>
                            <a href="<?= $url->createUrl(['/main/category/index', 'belong' => Category::BELONG_TAG]) ?>">لیست
                                برچسب ها</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-newspaper icon"></i><span>مقالات</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/blog/articles/create']) ?>">ساخت مقاله جدید</a>
                            <a href="<?= $url->createUrl(['/blog/articles/index']) ?>">لیست مقالات</a>
                            <a href="<?= $url->createUrl(['site/choosen-art']) ?>">مقالات برگزیده</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-comments icon"></i><span>نظر کاربران</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/community/index', 'parent_id' => 0, 'belong' => Community::BELONG_BLOGS, 'status' => Community::STATUS_PENDING]) ?>">جدید
                                ترین نظرات</a>
                            <a href="<?= $url->createUrl(['/community/index', 'parent_id' => 0, 'belong' => Community::BELONG_BLOGS, 'status' => Community::STATUS_SUBMIT]) ?>">نظرات
                                تایید شده</a>
                            <a href="<?= $url->createUrl(['/community/index', 'parent_id' => 0, 'belong' => Community::BELONG_BLOGS, 'status' => Community::STATUS_DENY]) ?>">نظرات
                                رد شده</a>
                        </section>
                    </section>

                    <section class="sidebar-part-title">مدیریت پکیج ها</section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-server icon"></i><span>دسته‌بندی پکیج</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/main/category/create', 'belong' => Category::BELONG_COURSE]) ?>">ساخت
                                دسته جدید</a>
                            <a href="<?= $url->createUrl(['/main/category/index', 'belong' => Category::BELONG_COURSE]) ?>">لیست
                                دسته‌ها</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-archive icon"></i><span>مدیریت پکیج ها</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/packages/create']) ?>">افزودن پکیج</a>
                            <a href="<?= $url->createUrl(['/packages/index']) ?>">لیست پکیج ها</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-calendar icon"></i><span>مدیریت جلسات</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/sections/create-group']) ?>">افزودن گروه جلسات</a>
                            <a href="<?= $url->createUrl(['/sections/groups']) ?>">لیست گروه جلسات</a>
                        </section>
                    </section>


                    <section class="sidebar-part-title">برنامه غذایی</section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-question-circle icon"></i><span>سوالات رژیم</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/questions/create', 'course_id' => 0]) ?>">افزودن سوال</a>
                            <a href="<?= $url->createUrl(['/questions/index', 'course_id' => 0]) ?>">لیست سوالات</a>
                            <a href="<?= $url->createUrl(['/faq', 'belong' => 'packages']) ?>">سوالات متداول پکیج</a>
                        </section>
                    </section>
                    <section class="sidebar-group-link">
                        <section class="sidebar-dropdown-toggle">
                            <i class="fas fa-heartbeat icon"></i><span>برنامه ها</span><i
                                class="fas fa-angle-left angle"></i>
                        </section>
                        <section class="sidebar-dropdown">
                            <a href="<?= $url->createUrl(['/regimes/create']) ?>">افزودن برنامه جدید</a>
                            <a href="<?= $url->createUrl(['/regimes/index']) ?>">لیست برنامه ها</a>
                        </section>
                    </section>

                    <section class="sidebar-part-title">آرشیو ها</section>
                    <a href="<?= $url->createUrl(['/media/index']) ?>" class="sidebar-link"><i class="fas fa-camera"></i><span>مدیا</span></a>
<!--                    <section class="sidebar-group-link">-->
<!--                        <section class="sidebar-dropdown-toggle">-->
<!--                            <i class="fas fa-server icon"></i><span>دسته‌بندی آرشیو</span><i-->
<!--                                class="fas fa-angle-left angle"></i>-->
<!--                        </section>-->
<!--                        <section class="sidebar-dropdown">-->
<!--                            <a href="--><?php //= $url->createUrl(['/main/category/create', 'belong' => Category::BELONG_ARCHIVE]) ?><!--">ساخت-->
<!--                                دسته جدید</a>-->
<!--                            <a href="--><?php //= $url->createUrl(['/main/category/index', 'belong' => Category::BELONG_ARCHIVE]) ?><!--">لیست-->
<!--                                دسته‌ها</a>-->
<!--                        </section>-->
<!--                    </section>-->
<!--                    <section class="sidebar-group-link">-->
<!--                        <section class="sidebar-dropdown-toggle">-->
<!--                            <i class="fas fa-bookmark icon"></i><span>مدیریت آرشیو ها</span><i-->
<!--                                class="fas fa-angle-left angle"></i>-->
<!--                        </section>-->
<!--                        <section class="sidebar-dropdown">-->
<!--                            <a href="--><?php //= $url->createUrl(['/archives/create']) ?><!--">ساخت آرشیو جدید</a>-->
<!--                            <a href="--><?php //= $url->createUrl(['/archives/index']) ?><!--">لیست آرشیو ها</a>-->
<!--                        </section>-->
<!--                    </section>-->

                    <section class="sidebar-part-title">ویدیو های آموزشی</section>
                    <a href="<?= $url->createUrl(['/site/guide', 'subject' => 'package']) ?>" class="sidebar-link"><i
                            class="fas fa-book"></i><span>ثبت و ویرایش پکیج</span></a>
                    <a href="<?= $url->createUrl(['/site/guide', 'subject' => 'regimes']) ?>" class="sidebar-link"><i
                            class="fas fa-book"></i><span>سوالات و برنامه های غذایی</span></a>

                </section>
            </section>
        </aside>
        <section id="main-body" class="main-body">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>
    </section>
    <?php $this->beginBody() ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
