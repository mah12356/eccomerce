<?php

namespace backend\controllers;

use backend\models\ChoosenArt;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\SendSms;
use common\models\Booking;
use common\models\Chats;
use common\models\Course;
use common\models\Diet;
use common\models\Factor;
use common\models\LoginForm;
use common\models\Packages;
use common\models\Register;
use common\models\RegisterCourses;
use common\models\RegisterSearch;
use common\models\SignupForm;
use common\models\Stu;
use common\models\Tickets;
use common\models\User;
use common\models\UserSearch;
use common\models\Coach;
use common\modules\blog\models\Articles;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use function Symfony\Component\String\s;

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
                'rules' => [
                    [
                        'actions' => ['login', 'logout', 'error', 'add-user', 'upload-audio'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['users', 'create-user', 'change-password', 'delete-user', 'define-role', 'assign-role', 'send-ads-sms','make-up'],
                        'allow' => true,
                        'roles' => ['dev', 'admin'],
                    ],
                    [
                        'actions' => ['find-user', 'signup', 'book','user-status', 'get-courses', 'get-all-courses', 'modify-register', 'modify', 'users-list', 'finance', 'guide', 'list'],
                        'allow' => true,
                        'roles' => ['dev', 'admin', 'coach'],
                    ],
                    [
                        'actions' => ['choosen-art'],
                        'allow' => true,
                        'roles' => ['dev', 'admin'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }
        $this->view->title = '.:: پنل مدیریت ::.';

        return $this->render('index');
    }
    public function actionChoosenArt(){
        $articles=Articles::find()->asArray()->all();
        $id= Yii::$app->request->post('id');
        $category_id= Yii::$app->request->post('category_id');
        $banner= Yii::$app->request->post('banner');
        $title= Yii::$app->request->post('title');
        $date= Yii::$app->request->post('date');
$r='ready';
        if ($this->request->isPost){
            $model= ChoosenArt::find()->where(['art_id'=>$id])->asArray()->one();

            if ($model === null){
                $choosen_art= new ChoosenArt();
                $choosen_art->art_id=$id;
                $choosen_art->category_id=$category_id;
                $choosen_art->banner=$banner;
                $choosen_art->title=$title;
                $choosen_art->date=$date;
                $choosen_art->save();
            }else{
                if ($model->status == 'unready'){
                    $model->status='ready';
                    $model->save();
                }else{
                    $model->status='unready';
                    $model->save();
                }
            }
        }
        return $this->render('choosen-art',[
            'articles'=>$articles
            ]);
    }
    public function actionUsers($role)
    {
        $searchModel = new UserSearch($role);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('users', [
            'role' => $role,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBook()
    {
        $t = Booking::find()->all();
        foreach ($t as $item){
            echo '<br>';
            echo $item->mobile;
        }
        print_r($t);
    }


    public function actionCreateUser($role)
    {
        $model = new SignupForm();

        $model->role = $role;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->signup()) {
                return $this->redirect(['users', 'role' => $role]);
            }
        }
        return $this->render('create_user', [
            'role' => $role,
            'model' => $model,
        ]);
    }

    public function actionChangePassword($id)
    {
        $model = new SignupForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->changePassword($id)) {
                return $this->redirect(['users', 'role' => Gadget::getRoleByUserId($id)]);
            }
        }
        return $this->render('change_password', [
            'model' => $model,
        ]);
    }

    public function actionDeleteUser($id)
    {
        $model = User::find()->where(['id' => $id])->one();
        if ($model) {
            $auth = Yii::$app->authManager;
            $role = Gadget::getRoleByUserId($id);
            if ($model->delete()) {
                $auth->revoke($auth->getRole($role), $model->id);
                $coach = Coach::find()->where(['user_id' => $id])->one();
                if ($coach) {
                    $coach->delete();
                }
            }
        }

        return Yii::$app->response->redirect(Yii::$app->request->referrer);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionGuide($subject)
    {
        switch ($subject) {
            case 'package':
                $this->view->title = 'ثبت و ویرایش پکیج';
                break;
            case 'regimes':
                $this->view->title = 'سوالات و برنامه های غذایی';
                break;
            default:
                $this->view->title = 'ویدیو های آموزشی';
                break;
        }
        return $this->render('guide', [
            'subject' => $subject,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionFindUser()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $validate = Gadget::validateMobileFormat($model->mobile);
                if ($validate['error']) {
                    $model->addError('mobile', 'شماره موبایل وارد شده اشتباه است');
                    return $this->render('find-user', [
                        'model' => $model,
                    ]);
                } else {
                    $model->mobile = $validate['mobile'];
                }

                $user = User::findOne(['username' => $model->mobile]);
                if ($user) {
                    return $this->redirect(['user-status', 'user_id' => $user->id]);
                } else {
                    $_SESSION['user_mobile'] = $model->mobile;
                    return $this->redirect(['signup']);
                }
            }
        }

        return $this->render('find-user', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (!isset($_SESSION['user_mobile']) || !$_SESSION['user_mobile']) {
            return $this->redirect(['find-user']);
        }

        $model = new SignupForm();

        $model->username = $_SESSION['user_mobile'];
        $model->mobile = $_SESSION['user_mobile'];
        $model->email = $_SESSION['user_mobile'] . '@gmail.com';
        $model->password = $_SESSION['user_mobile'];
        $model->role = User::ROLE_USER;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->signup()) {
                unset($_SESSION['user_mobile']);
                $user = User::findOne(['username' => $model->username]);
                if (!$user) {
                    return $this->redirect(['find-user']);
                }
                return $this->redirect(['user-status', 'user_id' => $user->id]);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionFinance()
    {
        $model = new Register();
        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (!isset($_POST['courses']) || !$_POST['courses']) {
                return $this->render('finance', [
                    'model' => $model,
                    'packages' => $packages,
                ]);
            }

            $registers = Register::find()->where(['package_id' => $model->package_id, 'payment' => Register::PAYMENT_ACCEPT])
                ->groupBy(['user_id'])->asArray()->all();

            $selectedCourses = $_POST['courses'];
            $ignoredCourses = ArrayHelper::map(Course::find()->where(['NOT IN', 'id', $selectedCourses])
                ->andWhere(['package_id' => $model->package_id])->asArray()->all(), 'id', 'id');

            $user_id = [];
            foreach ($registers as $register) {

                $allowCourses = RegisterCourses::find()->where(['IN', 'course_id', $selectedCourses])
                    ->andWhere(['register_id' => $register['id']])->asArray()->all();
                $restrictCourses = RegisterCourses::find()->where(['IN', 'course_id', $ignoredCourses])
                    ->andWhere(['register_id' => $register['id']])->asArray()->all();

//                $allowDiets = Diet::find()->where(['IN', 'course_id', $selectedCourses])->andWhere(['package_id' => $model->package_id])
//                    ->andWhere(['user_id' => $register['user_id']])->asArray()->all();
//                $restrictDiets = Diet::find()->where(['IN', 'course_id', $ignoredCourses])->andWhere(['package_id' => $model->package_id])
//                    ->andWhere(['user_id' => $register['user_id']])->asArray()->all();


//                Gadget::preview($register, false);
//                Gadget::preview($selectedCourses, false);
//                Gadget::preview($ignoredCourses, false);
//                Gadget::preview($allowCourses, false);
//                Gadget::preview($restrictCourses, false);
//                Gadget::preview($allowDiets, false);
//                Gadget::preview($restrictDiets);

                if (!$restrictCourses && count($allowCourses) == count($selectedCourses)) {
                    $user_id[] = $register['user_id'];
                }
            }

            $_SESSION['data']['package'] = $model->package_id;
            $_SESSION['data']['user_id'] = $user_id;

            return $this->redirect(['list']);
        }

        return $this->render('finance', [
            'model' => $model,
            'packages' => $packages,
        ]);
    }

    public function actionList()
    {
        $model = new Register();
        $searchModel = new RegisterSearch();
        $query = Register::find()->where(['IN', 'user_id', $_SESSION['data']['user_id']])
            ->andWhere(['package_id' => $_SESSION['data']['package'], 'payment' => Register::PAYMENT_ACCEPT])
            ->groupBy(['user_id'])->with('user.enroll');

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = [];
            if ($_POST['Register']['name']) {
                $usersWithName = User::find()->where(['like', 'name', $_POST['Register']['name']])->asArray()->all();
            } else {
                $usersWithName = [];
            }
            if ($_POST['Register']['lastname']) {
                $usersWithLastname = User::find()->where(['like', 'lastname', $_POST['Register']['lastname']])->asArray()->all();
            } else {
                $usersWithLastname = [];
            }

            $user_id = array_merge(ArrayHelper::map($usersWithName, 'id', 'id'), ArrayHelper::map($usersWithLastname, 'id', 'id'));

            if ($_POST['Register']['mobile']) {
                $usersWithMobile = User::find()->where(['mobile' => $_POST['Register']['mobile']])->asArray()->all();
                $user_id = ArrayHelper::map($usersWithMobile, 'id', 'id');
            }

            $query = Register::find()->where(['IN', 'user_id', $_SESSION['data']['user_id']])
                ->andWhere(['IN', 'user_id', $user_id])->andWhere(['package_id' => $_SESSION['data']['package'], 'payment' => Register::PAYMENT_ACCEPT])
                ->groupBy(['user_id'])->with('user.enroll');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('list', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUserStatus($user_id)
    {
        $user = User::find()->where(['id' => $user_id])->asArray()->one();
        $packages = Packages::find()->where(['!=', 'status', Packages::STATUS_INACTIVE])->asArray()->all();

        $actives = Factor::find()->where(['user_id' => $user_id, 'payment' => Factor::PAYMENT_ACCEPT])
            ->orderBy(['id' => SORT_DESC])->with('register.package')->with('register.courses.course')->asArray()->all();

        $model = new Register();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!isset($_POST['courses']) || !$_POST['courses']) {
                    Yii::$app->session->setFlash('danger', 'دوره‌ای انتخاب نشده است');
                    return $this->render('user-status', [
                        'user' => $user,
                        'packages' => $packages,
                        'model' => $model,
                    ]);
                }

                $courses = Course::find()->where(['IN', 'id', $_POST['courses']])->asArray()->all();
                if (!$courses) {
                    Yii::$app->session->setFlash('danger', 'دوره‌ای یافت نشد');
                    return $this->render('user-status', [
                        'user' => $user,
                        'packages' => $packages,
                        'model' => $model,
                    ]);
                }

                $response = $model->enroll($user_id, $model->package_id, $courses, true);
                if (!$response['error']) {
                    $factor = Factor::findOne(['id' => $response['data']['factor_id']]);
                    $factor->response_key = 'ActiveByAdmin_' . Jdf::jmktime();
                    $factor->save(false);

                    $result = Register::enrollPayment(true, $factor->id);
                    if (!$result['error']) {

                        $package = Packages::findOne(['id' => $model->package_id]);
                        $message = 'مهساآنلاینی عزیز، ' . $package->name . '  برای شما با موفقیت فعال شد
لغو ۱۱';
                     //   SendSms::SMS($user['username'], $message);

                        Yii::$app->session->setFlash('success', 'ثبت نام با موفقیت انجام شد');
                        return $this->redirect(['user-status', 'user_id' => $user_id]);
                    } else {
                        Yii::$app->session->setFlash('danger', $result['message']);
                    }
                } else {
                    Yii::$app->session->setFlash('danger', $response['message']);
                }
            }
        }

        return $this->render('user-status', [
            'user' => $user,
            'packages' => $packages,
            'model' => $model,
            'actives' => $actives,
        ]);
    }

    public function actionModifyRegister($id)
    {
        $factor = Factor::findOne(['id' => $id]);
        if (!$factor) {
            Yii::$app->session->setFlash('danger', 'فاکتور یافت نشد');
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $register = Register::findOne(['factor_id' => $id, 'payment' => Register::PAYMENT_ACCEPT]);
        if (!$register) {
            Yii::$app->session->setFlash('danger', '1');
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $user = User::findOne(['id' => $register->user_id]);
        if (!$user) {
            Yii::$app->session->setFlash('danger', '2');
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $package = Packages::findOne(['id' => $register->package_id]);
        if (!$package) {
            Yii::$app->session->setFlash('danger', '3');
            return Yii::$app->response->redirect(Yii::$app->request->referrer);
        }

        $registerCourses = RegisterCourses::find()->where(['register_id' => $register->id])
            ->with('course')->asArray()->all();

        $courses = ArrayHelper::map($registerCourses, 'id', 'course');

        $remain = Course::find()->where(['package_id' => $register->package_id])->andWhere(['NOT IN', 'id', ArrayHelper::map($courses, 'id', 'id')])
            ->asArray()->all();

        return $this->render('modify-register', [
            'user' => $user,
            'register' => $register,
            'package' => $package,
            'current' => $courses,
            'remain' => $remain,
        ]);
    }

    public function actionModify($register_id, $package_id, $course_id, $action)
    {
        $register = Register::findOne(['id' => $register_id]);
        if (!$register) {

        }

        $package = Packages::findOne(['id' => $package_id]);
        if (!$package) {

        }

        $course = Course::findOne(['id' => $course_id]);
        if (!$course) {

        }

       try {
            if ($action == 'remove') {
                if ($course->belong == Course::BELONG_DIET) {
                    if (!Diet::DeleteAll(['user_id' => $register->user_id, 'package_id' => $package->id, 'course_id' => $course->id])) {
                        Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        return $this->redirect(['modify-register', 'id' => $register->factor_id]);
                    }
                } else {
                    if (!RegisterCourses::deleteAll(['register_id' => $register->id, 'course_id' => $course->id])) {
                        Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        return $this->redirect(['modify-register', 'id' => $register->factor_id]);
                    }
                }
            } else {
                if ($course->belong == Course::BELONG_DIET || $course->belong == Course::BELONG_SHOCK_DIET) {
                    $j = 0;
                   // $interval = (int)($package['period'] / $course['count']);

                  //  for ($i = 1; $i <= $course['count']; $i++) {
                        $diet = new Diet();
                        $diet->user_id = $register->user_id;
                        $diet->register_id = $register->id;
                        $diet->package_id = $package_id;
                        $diet->course_id = $course->id;
                        $diet->type = $course->belong;
                        $diet->date = jdf::jmktime();
                        $diet->date_update = jdf::jmktime();
                       // $diet->validate();
                       // Gadget::preview($diet->errors);
                        if (!$diet->save()) {
                            Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                            return $this->redirect(['modify-register', 'id' => $register->factor_id]);
                        }
                        $j++;
                  //  }
                } else {
                    $registerCourses = new RegisterCourses();

                    $registerCourses->register_id = $register->id;
                    $registerCourses->course_id = $course->id;
                   // $registerCourses->validate();
                   // Gadget::preview($registerCourses->errors);
                    if (!$registerCourses->save()) {
                        Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        return $this->redirect(['modify-register', 'id' => $register->factor_id]);
                    }
                }
            }
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
        }
        Yii::$app->session->setFlash('success', 'اطلاعات با موفقیت ذخیره شد');
        return $this->redirect(['modify-register', 'id' => $register->factor_id]);
    }

    public function actionGetCourses($id)
    {
        $option = '';
        $courses = Course::find()->where(['package_id' => $id])->all();
        if ($courses) {
            foreach ($courses as $course) {
                if ($course['required'] == Course::REQUIRED_TRUE) {
                    $option .= '
                <div class="form-check d-none">
                    <label class="form-check-label">
                        <input type="checkbox" name="courses[' . $course->id . ']" class="form-check-input" value="' . $course->id . '" checked>
                        <span class="mr-3">' . $course->name . '</span>
                    </label>
                </div>
                ';
                } else {
                    $option .= '
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="courses[' . $course->id . ']" class="form-check-input" value="' . $course->id . '">
                        <span class="mr-3">' . $course->name . '</span>
                    </label>
                </div>
                ';
                }
            }
        } else {
            $option .= '
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="Activation[idItem]" class="form-check-input" disabled>
                        <span class="mr-3">دوره‌ای یافت نشد</span>
                    </label>
                </div>
            ';
        }
        return $option;
    }

    public function actionGetAllCourses($id)
    {
        $option = '';
        $courses = Course::find()->where(['package_id' => $id])->all();
        if ($courses) {
            foreach ($courses as $course) {
                $option .= '
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="courses[' . $course->id . ']" class="form-check-input" value="' . $course->id . '">
                            <span class="mr-3">' . $course->name . '</span>
                        </label>
                    </div>
                ';
            }
        } else {
            $option .= '
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="Activation[idItem]" class="form-check-input" disabled>
                        <span class="mr-3">دوره‌ای یافت نشد</span>
                    </label>
                </div>
            ';
        }
        return $option;
    }

    /**
     * @throws HttpException
     */
    public function actionDefineRole()
    {
//        $auth = Yii::$app->authManager;
//
//        $dev = $auth->createRole('dev');
//        $auth->add($dev);
//
//        $admin = $auth->createRole('admin');
//        $auth->add($admin);
//
//        $author = $auth->createRole('author');
//        $auth->add($author);
//
//        $coach = $auth->createRole('coach');
//        $auth->add($coach);
//
//        $user = $auth->createRole('user');
//        $auth->add($user);

        throw new HttpException(400, \Yii::t('app', 'عملیاتی برای اجرا یافت نشد'));
    }

    /**
     * @throws HttpException
     */
    public function actionAssignRole($id, $role)
    {
//        $auth = Yii::$app->authManager;
//
//        $role = $auth->getRole($role);
//        $auth->assign($role, $id);

        throw new HttpException(400, \Yii::t('app', 'عملیاتی برای اجرا یافت نشد'));
    }

    public function actionAddUser()
    {

    }

    public function actionUsersList()
    {
        $packages = Packages::find()->orderBy(['id' => SORT_DESC])->asArray()->all();
        $model = new Register();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $register = Register::find()->where(['package_id' => $model->package_id])
                    ->andWhere(['payment' => Register::PAYMENT_ACCEPT])
                    ->groupBy(['user_id'])->asArray()->all();

                $newPackages = Packages::find()->where(['>', 'id', $model->package_id])->asArray()->all();

                $userList = [];

                foreach ($register as $item) {
                    $enroll = Register::find()->where(['user_id' => $item['user_id']])
                        ->andWhere(['NOT IN', 'package_id', ArrayHelper::map($newPackages, 'id', 'id')])
                        ->andWhere(['!=', 'package_id', $model->package_id])
                        ->asArray()->all();

                    if (!$enroll) {
                        $userList[] = $item['user_id'];
                    }
                }

                $_SESSION['usersList'] = $userList;
            }
        }

        if (isset($_SESSION['usersList']) && $_SESSION['usersList']) {
            $searchModel = new UserSearch(User::ROLE_USER, $_SESSION['usersList']);
        } else {
            $searchModel = new UserSearch(User::ROLE_USER);
        }
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 100;

        return $this->render('users-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'packages' => $packages,
            'model' => $model,
        ]);
    }

    public function actionSendAdsSms()
    {
        $message = 'ظرفیت ثبت نام مهساآنلاین با تخفیف ۷۰ درصد فقط برای ۲۵۰ نفر باز شد.
برای ثبت نام در پکیج به پیج زیر دایرکت بدید.
@mahsaonlin_support

لغو ۱۱';

        // SendSms::SMS('09364307767', $message);
        // Gadget::preview(1);


        // $model = Register::find()->where(['payment' => Register::PAYMENT_ACCEPT)
        //     ->andWhere(['IN', 'package_id', [23]])->groupBy(['user_id'])
        //     ->with('user')->asArray()->all();
        
        $model = Booking::find()->where(['status' => Booking::STATUS_PENDING])
            ->asArray()->all();

        $j = 0;
        for ($i = 0; $i < count($model); $i++) {
            $validate = Gadget::validateMobileFormat($model[$i]['mobile']);
            if (!$validate['error']) {
                SendSms::SMS($validate['mobile'], $message);
                $j++;
            }
        }

        // $j = 0;
        // for ($i = 0; $i < count($model); $i++) {
        //     if ($model[$i]['user']) {
        //         SendSms::SMS($model[$i]['user']['username'], $message);
        //         $j++;
        //     }
        // }

        Gadget::preview($j);
    }
    
    // public function actionMakeUp(){
    //     $model = register::findAll(["package_id"=>24,'payment'=>'accept']);
    //     foreach($model as $user)
    //     {
    //         $course = new RegisterCourses();
    //         $course->register_id = $user->id;
    //         $course->course_id = 112;
    //         if($course->save())
    //         {
    //             echo "user ".$user->id." added completely";
    //         }else
    //         {
    //             echo "user ".$user->id." failed to add";
    //             break;
    //         }
            
    //     }
        
        
    // }
}
