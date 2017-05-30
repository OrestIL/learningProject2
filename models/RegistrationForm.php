<?php
/**
 * Created by PhpStorm.
 * User: oku
 * Date: 5/29/2017
 * Time: 12:48 PM
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\DataBase;

class RegistrationForm extends Model
{
    public $email;
    public $password;
    public $confirmPassword;


    public function rules()
    {
        return [
            [['email', 'password', 'confirmPassword'], 'required'],
            ['password', 'validatePassword'],
            ['email', 'validateEmail'],
        ];
    }

    public function validatePassword($password, $confirmPassword) {
        if(!$this->hasErrors()) {
            if ($password != "" && $confirmPassword != "" && $password === $confirmPassword) {
                $this->password = $password;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return "Incorrect password";
        }
    }

    public function validateEmail($email) {
        if ($email != "") {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return false;
            } else {
                $this->email = $email;
                return true;
            }
        } else {
            return "email is not correct";
        }
    }

    public function saveRegisteredUser() {
        $db = new DataBase();
        $emailValidationResult = $this->validateEmail($this->email);
        $passwordValidationResult = $this->validatePassword($this->password, $this->confirmPassword);
        if ($emailValidationResult === true && $passwordValidationResult === true){
            if($db->verifyEmail($this->email) === true){
                $db->insertNewUser($this->email, $this->password);
                return true;
            }
        } else {
            return false;
        }
    }
}