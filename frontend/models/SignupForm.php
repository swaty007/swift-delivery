<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $phone_number;
    public $password;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone_number', 'trim'],
            ['phone_number', 'match', 'pattern' => '/^\+1\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Incorrect phone number'],
            ['phone_number', 'required'],
            ['phone_number', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This phone number is already registered.'],
            ['phone_number', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->phone_number;
        $user->phone_number = $this->phone_number;
        $user->role = User::USER_ROLE_SUPPLIER;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $userSave = $user->save();

        return $userSave;
    }

    protected function sendApproveSms($user)
    {
        //TODO: Twilio send sms
    }
}
