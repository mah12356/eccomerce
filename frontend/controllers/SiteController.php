<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Navigator;
use common\components\Stack;
use common\models\Archives;
use common\models\Hints;
use common\models\Coach;
use common\models\Comments;
use common\models\Community;
use common\models\CourseSections;
use common\models\Diet;
use common\models\Factor;
use common\models\Faq;
use common\models\Media;
use common\models\Packages;
use common\models\Register;
use common\models\RegisterCourses;
use common\models\Sections;
use common\models\User;
use common\modules\blog\models\Articles;
use common\modules\blog\models\Gallery;
use common\modules\blog\models\Posts;
use common\modules\blog\models\Tags;
use common\modules\main\models\Category;
use common\modules\main\models\Config;
use common\modules\main\models\MetaTags;
use common\modules\main\models\Tokens;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\console\Response;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use common\models\SignupForm;
use common\models\Booking;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
//        unset($_SESSION['pages']);
        if (!isset($_SESSION['pages']) || !$_SESSION['pages']) {
            $_SESSION['pages'] = new Stack();
            $_SESSION['pages']->push(['/site/index', []]);
        }

        (new Navigator())->setUrl($_SESSION['pages'], Url::current());

        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * صفحه اصلی
     *
     * @return mixed
     */

      public function actionHh()
    {
       // Gadget::preview(Jdf::jdate('y/m/d',1700080200));
        $model = Diet::find()->where(['date'=>1700080200  ])->all();
       // Gadget::preview($model,true);
        foreach ($model as $item){
            $item->date_update = 1700080200;
            $item->status = Diet::STATUS_PENDING;
            $item->regime_id = 0;
            $item->save();
            Gadget::preview($item , false);
           // $item->delete();
//            $newDiet = new Diet();
//            $newDiet->status = Diet::STATUS_PENDING;
//            $newDiet->type = Diet::TYPE_SHOCK ;
//            $newDiet->register_id = $item->register_id;
//            $newDiet->package_id = $item->package_id;
//            $newDiet->course_id = 130;
//            $newDiet->date = Jdf::jmktime();
//            $newDiet->date_update = Jdf::jmktime();
//            $newDiet->user_id = $item->user_id;
//            $newDiet->validate();
//            //Gadget::preview($newDiet,false);
//            Gadget::preview($newDiet->save(),false);
        }
//        $var=Yii::getAlias('@upload');
//       // $ffmpeg = $var."/bin/ffmpeg.exe";
//// $uploaddir is my file upload path
//        $video = Yii::getAlias('@upload').'/input.mp4';
//        $video2 = Yii::getAlias('@upload').'/output.mp4';
////where to save the image
//        $iname = basename($video, ".mp4");
//        $image = Yii::getAlias('@upload').'/'.$iname.'_img.jpg';
////time to take screenshot at
//        $interval = 5;
////screenshot size  pn
//        $size = '640x480';
////ffmpeg command
//    //    $cmd="$ffmpeg -i ".$video." -ss 00:00:01.100 -f image2 -vframes 1 ".$image;
//       $tt = "ffmpeg -i ".$video." -af volume=0.5 ".$video2;
//
//
//        print_r(exec($tt));
    }
    public function actionHm()
    {
        //phpinfo();
        $var=Yii::getAlias('@upload');
        // $ffmpeg = $var."/bin/ffmpeg.exe";
// $uploaddir is my file upload path
        $video = Yii::getAlias('@upload').'/inputpp.mp4';
        $video2 = Yii::getAlias('@upload').'/output.mp4';
//where to save the image
       // $iname = basename($video, ".mp4");
       // $image = Yii::getAlias('@upload').'/'.$iname.'_img.jpg';
//time to take screenshot at
        $interval = 5;
//screenshot size  pn
        $size = '640x480';
        //    $cmd="$ffmpeg -i ".$video." -ss 00:00:01.100 -f image2 -vframes 1 ".$image;
        echo '1';
        $tt = "ffmpeg -i ".$video." -af volume=0.5 ".$video2;
//        print_r("var_dump(exec('which echo')) <br>");
//        echo '<br>';
//        var_dump(exec('2ffmpeg -i /home1/mahsaonlin/public_html/upload/inputpp.mp4 -af volume=0.5 /home1/mahsaonlin/public_html/upload/mnputpp.mp4'));
//        echo '<br>';
//        print_r("is_file(exec('which echo')) <br>");
//        echo '<br>';
//        echo '<br>';
//        var_dump(is_file(exec('ffmpeg2 -i /home1/mahsaonlin/public_html/upload/inputpp.mp4 -af volume=0.5 /home1/mahsaonlin/public_html/upload/mnputpp.mp4')));
//        echo '<br>';
//        print_r("is_executable(exec('which echo')) <br>");
//        echo '<br>';
//        var_dump(is_executable(exec('2ffmpeg -i /home1/mahsaonlin/public_html/upload/inputpp.mp4 -af volume=0.5 /home1/mahsaonlin/public_html/upload/mnputpp.mp4')));
//        echo '<br>';
//        echo '<br>';
        $cmd ="/home1/mahsaonlin/public_html/upload/ffmpeg -i /home1/mahsaonlin/public_html/upload/inputpp.mp4 -af volume=0.5 /home1/mahsaonlin/public_html/upload/0002.mp4 &" ;
//        $cmdstr = $cmd;
//        $locale = 'en_IN.UTF-8';
//        setlocale(LC_ALL, $locale);
//        putenv('LC_ALL='.$locale);
        echo system($cmd);
       // system("ffmpeg -i /home1/mahsaonlin/public_html/upload/inputpp.mp4 -af volume=0.5 /home1/mahsaonlin/public_html/upload/0tpp.mp4 &");
       // print_r(exec('ls'));

      //  print_r(shell_exec($tt));
        echo '2';
    }
    // public function actionAmir()
    // {
    //     Booking::deleteAll();
    // }
    public function actionIndex($key = '')
    {
        $_SESSION['appMode'] = false;
//        unset($_SESSION['appMode']);
        if ($key == 'mahsaonlin-app') {
            $_SESSION['appMode'] = true;
        }
//        if ($key) {
//            $_SESSION['appMode'] = false;
//            if ($key == 'mahsaonlin-app') {
//                $_SESSION['appMode'] = true;
//            }
//        }else {
//            if (!isset($_SESSION['appMode'])) {
//                $_SESSION['appMode'] = false;
//                if ($key == 'mahsaonlin-app') {
//                    $_SESSION['appMode'] = true;
//                }
//            }
//        }

//        Gadget::preview($_SESSION);

        if ($_SESSION['appMode']) {
            $userOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);
            if ($userOs['belong'] == 'mobile') {
                return $this->redirect(['login']);
            }
        }

        $this->layout = 'home';
        $this->view->title = Config::getKeyContent(Config::KEY_SITE_TITLE);
        MetaTags::setMetaTags(0, MetaTags::BELONG_HOME);

        $post = Posts::find()->where(['page_id' => 0])->with('images')->asArray()->all();
        $process = Gallery::find()->where(['parent_id' => 0, 'belong' => Gallery::BELONG_HOME, 'type' => Gallery::TYPE_COMPARE])
            ->limit(4)->asArray()->all();

        $packages = Packages::find()->where(['status' => Packages::STATUS_READY, 'preview' => Packages::PREVIEW_ON])->andWhere(['=', 'discount', 0])
            ->with('courses')->with('cat')->asArray()->all();
        $offers = Packages::find()->where(['status' => Packages::STATUS_READY, 'preview' => Packages::PREVIEW_ON])->andWhere(['!=', 'discount', 0])
            ->with('courses')->with('cat')->asArray()->all();
        $comments = Comments::find()->orderBy(new Expression('rand()'))->asArray()->all();

        $selectedArticle = Articles::find()->where(['preview' => Articles::PREVIEW_ON, 'publish' => Articles::PUBLISH_TRUE])->limit(6)->asArray()->all();
        $newArticle = Articles::find()->where(['NOT IN', 'id', ArrayHelper::map($selectedArticle, 'id', 'id')])
            ->andWhere(['publish' => Articles::PUBLISH_TRUE])->orderBy(['modify_date' => SORT_DESC])->limit(6)->asArray()->all();

        $faq = Faq::find()->where(['belong' => Faq::BELONG_HOME])->orderBy(['sort' => SORT_ASC])->asArray()->all();

        return $this->render('index', [
            'post' => $post,
            'process' => $process,
            'packages' => $packages,
            'offers' => $offers,
            'comments' => $comments,
            'selectedArticle' => $selectedArticle,
            'newArticle' => $newArticle,
            'faq' => $faq,
        ]);
    }



    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }else {
            $this->layout = 'panel';
        }

        $this->view->title = 'پنل کاریری : ' . Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->lastname;

        $register = Register::find()->where(['user_id' => Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])->asArray()->all();
        $registerCourses = RegisterCourses::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->asArray()->all();
        $courseSections = CourseSections::find()->where(['IN', 'course_id', ArrayHelper::map($registerCourses, 'course_id', 'course_id')])
            ->asArray()->all();

        $sections = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
            ->andWhere(['mood' => Sections::MOOD_PLAYING])->asArray()->all();
        $diet = Diet::find()->where(['user_id' => Yii::$app->user->id, 'status' => Diet::STATUS_WAIT_FOR_ANSWERS])->asArray()->all();
        $factor = Factor::find()->where(['user_id' => Yii::$app->user->id, 'payment' => Factor::PAYMENT_PENDING])->asArray()->all();

        $notification = [];

        foreach ($sections as $item) {
            $index = [];
            $index['type'] = 'section';
            $index['id'] = $item['id'];
            $index['title'] = $item['title'];

            $notification[] = $index;
        }

        foreach ($diet as $item) {
            $index = [];
            $index['type'] = 'diet';
            $index['id'] = $item['id'];
            $index['title'] = 'برنامه غذایی';

            $notification[] = $index;
        }

        foreach ($factor as $item) {
            $index = [];
            $index['type'] = 'factor';
            $index['id'] = $item['id'];
            $index['title'] = 'شناسه ' . Gadget::convertToPersian($item['id']);

            $notification[] = $index;
        }


        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])
            ->andWhere(['>', 'end_register', Jdf::jmktime()])->asArray()->all();
        $blogs = Articles::find()->where(['publish' => Articles::PUBLISH_TRUE])->orderBy(['modify_date' => SORT_DESC])->limit(2)->asArray()->all();

        $hints = null;

        $package = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $register = Register::find()->where(['user_id' => Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($package, 'id', 'id')])
            ->asArray()->all();

        if ($register) {
            $hints = Hints::find()->where(['belong' => Hints::BELONG_HOME_PAGE])->andWhere(['or',['IN','typePackage',ArrayHelper::map($register, 'package_id', 'package_id')],['typePackage'=>'public']])
                ->groupBy('title')->orderBy(['id' => SORT_DESC])->limit(3)->asArray()->all();
          //  $hints = Hints::find()->where(['belong' => Hints::BELONG_HOME_PAGE])->orderBy(['id' => SORT_DESC])->limit(3)->asArray()->all();
        }

        return $this->render('dashboard', [
            'notification' => $notification,
            'packages' => $packages,
            'blogs' => $blogs,
            'hints' => $hints,
        ]);
    }

    /**
     * نمایش لیست پکیج های موجود
     *
     * @return mixed
     */
    public function actionPackages()
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $this->view->title = 'لیست پکیج ها';

        $model = Packages::find()->where(['status' => Packages::STATUS_READY, 'preview' => Packages::PREVIEW_ON])->orderBy(['start_register' => SORT_ASC])
            ->with('cat')->with('courses')->orderBy(['id' => SORT_DESC])->asArray()->all();

        return $this->render('packages', [
            'model' => $model,
        ]);
    }

    /**
     * نمایش جزئیات پکیج
     *
     * @param int $id
     * @return Response|string|\yii\web\Response
     */
    public function actionPackageDetail(int $id)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = Packages::find()->where(['id' => $id])->with('cat')->with('courses')->asArray()->one();
        if (!$model) {
            return $this->redirect(['index']);
        }

        return $this->render('package-detail', [
            'model' => $model,
        ]);
    }

    /**
     * باشگاه مربیان
     *
     * @return mixed
     */
    public function actionClub(): string
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = Coach::find()->with('user')->asArray()->all();

        return $this->render('club', [
            'model' => $model,
        ]);
    }

    /**
     * نمایش اطلاعات مربی
     *
     * @return Response|string|\yii\web\Response
     */
    public function actionCoach($id)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = Coach::find()->where(['user_id' => $id])->with('user')->with('course')->asArray()->one();
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        return $this->render('coach', [
            'model' => $model,
        ]);
    }

    /**
     * لیست تمامی بلاگ ها
     * @return string|Response|\yii\web\Response
     */
    public function actionBlogs($category = 0)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $this->view->title = 'مجله مهسا آنلاین';

        if ($category == 0) {
            $model =Articles::find()->orderBy(['modify_date'=>SORT_DESC])->asArray()->all();

        } else {
            $cat = Category::find()->where(['id' => $category, 'belong' => Category::BELONG_BLOG])->with('articles')->asArray()->all();
            $children = Category::find()->where(['parent_id' => $category, 'belong' => Category::BELONG_BLOG])->with('articles')->asArray()->all();
            $model = array_merge($cat, $children);
        }
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }
        $community = Community::find()->where(['belong'=>'blog'])->asArray()->all();
        return $this->render('blogs', [
            'model' => $model,
            'category' => $category,
            'community'=>$community
        ]);
    }

    /**
     * نمایش بلاگ ها بر اساس برچسب ها
     * @param int $id
     * @return string|Response|\yii\web\Response
     */
    public function actionTags(int $id)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $tag = Category::findOne(['id' => $id]);
        if (!$tag) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $this->view->title = $tag->title;

        $posts = Posts::find()->where(['page_id' => $id, 'belong' => Posts::BELONG_TAGS])->with('images')->asArray()->all();

        return $this->render('tags', [
            'tag' => $tag,
            'model' => Articles::find()->where(['IN', 'id', ArrayHelper::map(Tags::find()->where(['tag_id' => $id])->asArray()->all(), 'id', 'article_id')])
                ->andWhere(['publish' => Articles::PUBLISH_TRUE])->orderBy(['id' => SORT_DESC])->with('cat')->asArray()->all(),
            'posts' => $posts,
        ]);
    }

    /**
     * نمایش یک بلاگ
     * @param int $id
     * @return string|Response|\yii\web\Response
     */
    public function actionArticleView(int $id)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = Articles::find()->where(['id' => $id])->with('cat')->with('tags.tag')->with('posts.images')->asArray()->one();
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $this->view->title = $model['page_title'];

        MetaTags::setMetaTags($id, MetaTags::BELONG_BLOG);

        $related = Articles::find()->where(['category_id' => $model['category_id']])->andWhere(['!=', 'id', $model['id']])
            ->orderBy(new Expression('rand()'))->limit(4)->asArray()->all();

        $comments = Community::find()->where(['parent_id' => $id, 'status' => Community::STATUS_SUBMIT])
            ->orderBy(['date' => SORT_DESC])->with('user')->asArray()->all();

        $community = new Community();
        $signupForm = new SignupForm();

        if ($this->request->isPost) {
            if ($community->load($this->request->post()) && $signupForm->load($this->request->post())) {
                $signupForm->mobile = Gadget::convertToEnglish($signupForm->mobile);
                $user = User::findOne(['mobile' => $signupForm->mobile]);
                if (!$user) {
                    $signupForm->username = $signupForm->mobile;
                    $signupForm->email = $signupForm->mobile . '@gmail.com';
                    $signupForm->password = $signupForm->mobile;
                    $signupForm->role = User::ROLE_USER;

                    if ($signupForm->signup()) {
                        $user = User::findOne(['mobile' => $signupForm->mobile]);
                        if ($user) {
                            $user_id = $user->id;
                        } else {
                            $user_id = 0;
                        }
                    } else {
                        $user_id = 0;
                    }
                } else {
                    $user_id = $user->id;
                }

                if ($user_id != 0) {
                    $community->user_id = $user_id;
                    $community->parent_id = $id;
                    $community->belong = Community::BELONG_BLOGS;
                    $community->date = Jdf::jmktime();

                    if ($community->save()) {
                        $community = new Community();
                        $signupForm = new SignupForm();
                    }
                }
            }
        }

        return $this->render('article-view', [
            'model' => $model,
            'related' => $related,
            'comments' => $comments,
            'community' => $community,
            'signupForm' => $signupForm,
        ]);
    }

    /**
     * صفحه تماس با ما
     *
     * @return mixed
     */
    public function actionContact()
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $this->view->title = 'تماس با ما';

        $email = Config::getKeyContent(Config::KEY_EMAIL);
        $address = Config::getKeyContent(Config::KEY_ADDRESS);
        $phone = Config::getKeyContent(Config::KEY_PHONE);
        $mobile = Config::getKeyContent(Config::KEY_MOBILE);

        return $this->render('contact', [
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'mobile' => $mobile,
        ]);
    }

    /**
     * صفحه درباره ما
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $this->view->title = 'درباره ما';

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }

        MetaTags::setMetaTags(-1, MetaTags::BELONG_PAGE);
        $posts = Posts::find()->where(['page_id' => -1])->with('images')->asArray()->all();
        return $this->render('about', [
            'posts' => $posts,
        ]);
    }

    public function actionArchives()
    {

    }

    public function actionArchiveView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['signup']);
        }

        $model = Archives::find()->where(['id' => $id])->with('category')->with('content')->asArray()->one();
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        MetaTags::setMetaTags($id, MetaTags::BELONG_ARCHIVE);
        $this->view->title = $model['title'];

//        Gadget::preview($model);

        return $this->render('archive-view', [
            'model' => $model,
        ]);
    }

    public function actionDemoView(int $id)
    {
        $model = Articles::find()->where(['id' => $id])->with('cat')->with('tags.tag')->with('posts.images')->with('posts.similar')->asArray()->one();
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $this->view->title = $model['title'];

        MetaTags::setMetaTags($id, MetaTags::BELONG_BLOG);

        $related = Articles::find()->where(['category_id' => $model['category_id']])->andWhere(['!=', 'id', $model['id']])
            ->orderBy(new Expression('rand()'))->limit(4)->asArray()->all();

        $comments = Community::find()->where(['parent_id' => $id, 'status' => Community::STATUS_SUBMIT])
            ->orderBy(['date' => SORT_DESC])->with('user')->asArray()->all();

        $community = new Community();
        $signupForm = new SignupForm();

        if ($this->request->isPost) {
            if ($community->load($this->request->post()) && $signupForm->load($this->request->post())) {
                $signupForm->mobile = Gadget::convertToEnglish($signupForm->mobile);
                $user = User::findOne(['mobile' => $signupForm->mobile]);
                if (!$user) {
                    $signupForm->username = $signupForm->mobile;
                    $signupForm->email = $signupForm->mobile . '@gmail.com';
                    $signupForm->password = $signupForm->mobile;
                    $signupForm->role = User::ROLE_USER;

                    if ($signupForm->signup()) {
                        $user = User::findOne(['mobile' => $signupForm->mobile]);
                        if ($user) {
                            $user_id = $user->id;
                        } else {
                            $user_id = 0;
                        }
                    } else {
                        $user_id = 0;
                    }
                } else {
                    $user_id = $user->id;
                }

                if ($user_id != 0) {
                    $community->user_id = $user_id;
                    $community->parent_id = $id;
                    $community->belong = Community::BELONG_BLOGS;
                    $community->date = Jdf::jmktime();

                    if ($community->save()) {
                        $community = new Community();
                        $signupForm = new SignupForm();
                    }
                }
            }
        }

        return $this->render('article-view', [
            'model' => $model,
            'related' => $related,
            'comments' => $comments,
            'community' => $community,
            'signupForm' => $signupForm,
        ]);
    }

    public function actionGuide()
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $this->view->title = 'ویدیوهای آموزشی مهساآنلاین';

        $model = Media::find()->where(['type' => Media::TYPE_GUIDE])->asArray()->all();

        return $this->render('guide', [
            'model' => $model,
        ]);
    }

    public function actionMedia($id)
    {
        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = Media::find()->where(['id' => $id])->asArray()->one();
        if (!$model) {
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $this->view->title = $model['title'];

        return $this->render('media', [
            'model' => $model,
        ]);
    }

    public function actionUsageVideos($section)
    {
        $this->view->title = 'ویدیوهای آموزشی مهساآنلاین';

        return $this->render('usage-videos', [
            'section' => $section,
        ]);
    }

    /**
     * دریافت شماره تلفن کاربر برای ورود
     *
     * @return mixed
     */
    public function actionLogin($callBack = null)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['dashboard']);
        }

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }

        $model = new User();

        $_SESSION['mobile'] = '';
        $_SESSION['callBack'] = $callBack;

        if ($model->load(Yii::$app->request->post())) {
            $validate = Gadget::validateMobileFormat($model->mobile);
            if (!$validate['error']) {
                $_SESSION['mobile'] = $validate['mobile'];
                Tokens::sendVerifyCode($validate['mobile'], $validate['debug']);
                return $this->redirect(['verify']);
            } else {
                $model->addError('mobile', Message::WRONG_MOBILE);
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * دریافت کد تایید از کاربر
     *
     * @return mixed
     */
    public function actionVerify()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }

        if (!isset($_SESSION['mobile']) || !$_SESSION['mobile']) {
            return $this->redirect(['login']);
        }

        $model = new Tokens();

        if ($model->load(Yii::$app->request->post())) {
            if (!Tokens::findOne(['mobile' => $_SESSION['mobile'], 'code' => Gadget::convertToEnglish($model->code)])) {
                $model->addError('code', Message::WRONG_VERIFY_CODE);
                return $this->render('verify', [
                    'model' => $model,
                ]);
            }

            $user = User::findOne(['username' => $_SESSION['mobile']]);
            if ($user) {
                $loginForm = new LoginForm();

                $loginForm->username = $_SESSION['mobile'];
                $loginForm->mobile = $_SESSION['mobile'];
                $loginForm->password = $_SESSION['mobile'];
                $loginForm->rememberMe = true;

                if ($loginForm->login()) {
                    if ($_SESSION['callBack']) {
                        return $this->redirect([$_SESSION['callBack']]);
                    } else {
                        return $this->redirect(['dashboard']);
                    }
                }
            } else {
                return $this->redirect(['signup']);
            }
        }

        return $this->render('verify', [
            'model' => $model,
        ]);
    }

    /**
     * ثبت نام
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }

        if (!isset($_SESSION['mobile']) || !$_SESSION['mobile']) {
            return $this->redirect(['login']);
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {

            $model->mobile = Gadget::convertToEnglish($_SESSION['mobile']);
            $model->username = $model->mobile;
            $model->email = $model->mobile . '@gmail.com';
            $model->password = $model->mobile;
            $model->role = User::ROLE_USER;

            if (User::findOne(['username' => $model->mobile])) {
                return $this->redirect(['login']);
            }

            if ($model->validate() && $model->signup()) {
                $loginForm = new LoginForm();

                $loginForm->username = $model->mobile;
                $loginForm->mobile = $model->mobile;
                $loginForm->password = $model->mobile;

                if ($loginForm->login()) {
                    return $this->redirect(['dashboard']);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * پنل کاربری
     *
     * @return mixed
     */


    /**
     * اطلاعات حساب کاربری
     *
     * @return mixed
     */
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }else {
            $this->layout = 'panel';
        }

        $this->view->title = 'اطلاعات کاربری';

        $model = User::find()->where(['id' => Yii::$app->user->id])->asArray()->one();

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (Yii::$app->user->isGuest) {
            if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
                header('Location: https://mahsaonlin.com?key=mahsaonlin-app');
                exit;
            }else {
                return $this->goHome();
            }
        }

        if (isset($_SESSION['appMode']) && $_SESSION['appMode']) {
            $user = User::findOne(['id' => Yii::$app->user->id]);
            $user->generateAuthKey();
            $user->save();

            Yii::$app->user->logout();

            header('Location: https://mahsaonlin.com?key=mahsaonlin-app');
            exit;
        }

        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionNavigate()
    {
//        Gadget::preview($_SESSION['pages']->print());
        if (!$_SESSION['pages']->isEmpty()) {
            $_SESSION['pages']->pop();
            $peek = $_SESSION['pages']->peek();
            $_SESSION['pages']->pop();
            return $this->redirect(Navigator::getUrl($peek));
        }
        return $this->redirect(['/site/index']);
    }

    public function actionPublishArticles()
    {
        $model = Articles::find()->where(['publish' => Articles::PUBLISH_FALSE])->orderBy(['id' => SORT_ASC])->one();

        if ($model) {
            $model->publish = Articles::PUBLISH_TRUE;
            $model->save();
        }
    }

    public function actionAssignRegimes()
    {
        Diet::assignRegimes();
    }

    public function actionDownloadApp()
    {
        $path = \Yii::getAlias('@upload') . '/statics/mahsaonlin.apk';

        if (file_exists($path)) {
            return \Yii::$app->response->sendFile($path);
        }
    }
}
