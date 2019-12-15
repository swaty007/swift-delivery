<?php
namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * CreateUser form
 */
class CreateUser extends Model
{
    public $username;
    public $phone_number;
    public $role;
    public $status;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username is already registered.'],
            ['phone_number', 'trim'],
            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Incorrect phone number'],
            ['phone_number', 'required'],
            ['phone_number', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone number is already registered.'],
            ['phone_number', 'string', 'min' => 2, 'max' => 255],

            ['role', 'required'],
            ['status', 'required'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function createUser()
    {

        $user = new User();
        $user->username = $this->username;
        $user->phone_number = $this->phone_number;
        $user->role = $this->role;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save();
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
