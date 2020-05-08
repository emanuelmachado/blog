<?php

defined('BASEPATH') || exit('No direct script access allowed');

class DefaultValidation{

    private $errors;

    public function __construct(){
        $this->errors = array();
    }

    public function isValid(){
        return $this->hasErrors() === false;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function hasErrors(){
        return isset($this->errors) && is_array($this->errors) && count($this->errors) > 0;
    }

    public function push($code, $message, $severity = SeverityEnum::CRITICAL){
        array_push($this->errors,array("code" => $code, "message" => $message, "severity" => $severity));
    }
}
/* End of file DefaultValidation.php */
/* Location: ./models/Validation/DefaultValidation.php */