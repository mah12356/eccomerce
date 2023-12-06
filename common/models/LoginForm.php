<?php

namespace common\models;

use common\components\Gadget;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $mobile;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username' ,'password'], 'required'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['mobile', 'validateMobile'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'نام کاربری'),
            'password' => Yii::t('app', 'رمز عبور'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
            'rememberMe' => Yii::t('app', 'مرا به خاطر بسپار'),
        ];
    }

    /**
     * Validates the mobile num.
     * This method serves as the inline validation for mobile.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->mobile = Gadget::convertToEnglish($this->mobile);
            if (is_numeric($this->mobile)){
                $numbers = str_split($this->mobile);
                if (count($numbers) != 11 && $numbers[0] != 0 && $numbers[1] != 9) {
                    $this->addError($attribute, 'شماره تلفن وارد شده اشتباه است.');
                }
            }else{
                $this->addError($attribute, 'شماره تلفن وارد شده اشتباه است.');
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'نام کاربری و یا رمز عبور اشتباه است.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}