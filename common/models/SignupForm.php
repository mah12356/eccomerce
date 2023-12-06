<?php

namespace common\models;

use common\components\Gadget;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $name;
    public $lastname;
    public $email;
    public $mobile;
    public $password;
    public $role;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'نام کاربری وارد شده تکراری است.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'ایمیل وارد شده تکراری است.'],

            ['mobile', 'trim'],
            ['mobile', 'required'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => 'شماره تلفن وارد شده تکراری است.'],
            ['mobile', 'validateMobile'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

//            ['role', 'required'],
//            ['role', 'in', 'range' => [User::ROLE_DEV, User::ROLE_ADMIN, User::ROLE_USER]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'نام کاربری'),
            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'email' => Yii::t('app', 'ایمیل'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
            'password' => Yii::t('app', 'رمز عبور'),
        ];
    }

    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->mobile = Gadget::convertToEnglish($this->mobile);
            if (is_numeric($this->mobile)){
                $numbers = str_split($this->mobile);
                if (count($numbers) != 11 || $numbers[0] != 0 || $numbers[1] != 9) {
                    $this->addError($attribute, 'شماره تلفن وارد شده صحیح نمی‌باشد');
                }
            }else{
                $this->addError($attribute, 'شماره تلفن وارد شده صحیح نمی‌باشد');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->email = $this->email;
        $user->mobile = $this->mobile;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if ($user->save()){
            $auth = Yii::$app->authManager;

            $role = $auth->getRole($this->role);
            $auth->assign($role, $user->getId());

            return true;
        }
        return false;
//        return $user->save() && $this->sendEmail($user);
    }

    public function changePassword($id)
    {
        $user = User::find()->where(['id' => $id])->one();

        if ($user){
            $user->setPassword($this->password);
            return $user->save();
        }
        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
