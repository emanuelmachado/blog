<?php defined('BASEPATH') || exit('No direct script access allowed');

class TagService extends DefaultService{
    
    protected $CI;
    protected $dal;

    public function __construct() {
        $this->CI = & get_instance();
        $this->dal = new TagDAL();
    }

}