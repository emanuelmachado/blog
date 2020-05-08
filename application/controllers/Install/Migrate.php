<?php

class Migrate extends CI_Controller
{

    public function __construct()
	{
        parent::__construct();
        $this->load->library('migration');
    }
  
    public function index()
    {
        if ($this->migration->latest() === FALSE)
        {
            show_error($this->migration->error_string());
        }else{
            echo "Migrações executadas com sucesso!";
        }
    }
  
    public function version($version)
    {
        $this->load->helper(array('db'));
        if($this->migration->version($version) === FALSE)
        {   
            show_error($this->migration->error_string());
        }else{
            echo "Migrações executadas com sucesso!";
        }
    }

    public function latest()
    {
        if($this->migration->latest() === FALSE)
        {   
            show_error($this->migration->error_string());
        }else{
            echo "Migrações executadas com sucesso!";
        }
    }

}