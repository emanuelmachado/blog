<?php defined('BASEPATH') || exit('No direct script access allowed');

class TagDAL extends ORM_Lean{

    protected $CI;

    public function __construct() {
        parent::__construct();
        
        $this->initialize("tags", "TagEntity", "id", "t");

        $this->CI = & get_instance();
    }
    
}