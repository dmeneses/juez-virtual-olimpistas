<?php

namespace User\Model;

/**
 * Class that defines a user.
 *
 * @author Daniela Meneses
 */
class User {
    const ID = 'user_id';
    const NAME = 'name';
    const LASTNAME = 'lastname';
    const BIRTH_DATE = 'birth_date';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const INSTITUTION = 'institution';
    const CITY = 'city';
    
    public $user_id;
    public $name;
    public $lastname;
    public $birth_date;
    public $email;
    public $password;
    public $institution;
    public $city;
    
    public function exchangeArray($data) {
          $this->training_id = (!empty($data[self::ID])) ? $data[self::ID] : null;
          $this->name = (!empty($data[self::NAME])) ? $data[self::NAME] : null;
          $this->lastname = (!empty($data[self::LASTNAME])) ? $data[self::LASTNAME] : null;
          $this->birth_date = (!empty($data[self::BIRTH_DATE])) ? $data[self::BIRTH_DATE] : null;
          $this->email = (!empty($data[self::EMAIL])) ? $data[self::EMAIL] : null;
          $this->password = (!empty($data[self::PASSWORD])) ? $data[self::PASSWORD] : null;
          $this->institution = (!empty($data[self::INSTITUTION])) ? $data[self::INSTITUTION] : null;
          $this->city = (!empty($data[self::CITY])) ? $data[self::CITY] : null;     
    }
}
