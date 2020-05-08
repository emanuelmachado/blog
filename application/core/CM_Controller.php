<?php defined('BASEPATH') || exit('No direct script access allowed');

class CM_Controller extends CI_Controller {

    public $layout;
    public $title;
    public $title_page;
    public $menu;
    public $breadcrumb;
    public $css;
    public $js;
    public $loader;

    
    function __construct() {
        parent::__construct();
      
        $this->layout = '';
        $this->title = $this->config->item("systemName");
        $this->title_page = "";
        $this->menu = "";
        $this->breadcrumb = "";
        $this->css = array();
        $this->js = array();
    }

    public function loadModels($models) {
        if (count($models) > 0) {
            foreach ($models as $model) {
                $this->load->model($model);
            }
        }
    }
    
    public function checkGroup($userType){
        if(UserTypeEnum::ADMIN == $userType && $this->ion_auth->in_group("admins")){ 
            return true;
        }else if(UserTypeEnum::MEMBER == $userType && $this->ion_auth->in_group("member")){
            return true;
        }else{ 
            redirect('login');
        }
    }

    public function getUserLogged(){
        return $this->ion_auth->user()->row();
    }
}