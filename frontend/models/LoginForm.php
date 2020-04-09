<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone_number;
    public $password;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{4}$/', 'message' => 'Incorrect phone number'],
            ['phone_number', 'required'],

            [['phone_number', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
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
                $this->addError($attribute, 'Incorrect phone number or password.');
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
        if ($this->validate() && $this->getUser()->role == User::USER_ROLE_SUPPLIER) {
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * Yii::$app->params['supplierSessionDurationDays']);
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
            $this->_user = User::findOne(['username' => $this->phone_number]);
        }

        return $this->_user;
    }
}
