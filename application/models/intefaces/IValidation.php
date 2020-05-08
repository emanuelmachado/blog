<?php 
defined('BASEPATH') || exit('No direct script access allowed');

interface IValidation{
    public function validate($object);
    public function isValid();
}