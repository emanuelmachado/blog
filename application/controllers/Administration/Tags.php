<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends CM_Controller {

	public function index()
	{
        $this->loadModels(array("interfaces/Validation",
                                "enums/SeverityEnum","services/DefautService",
                                "valication/DefaultValidation","valication/TagValidation",
                                "entity/TagEntity","dal/TagDAL", "services/TagService"));

        $service = new TagService;

        $data["tags"] = $service->getAll();

		$this->load->view('administration/tags/index',$data);
	}
}