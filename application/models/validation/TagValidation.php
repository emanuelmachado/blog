<?php

defined('BASEPATH') || exit('No direct script access allowed');

class TagValidation extends DefaultValidation implements IValidation{

    public function validate($tag){ 
        $this->hasName($tag); 
    }

    public function validateEdit($tag){
        $this->hasId($tag); 
        $this->hasName($tag);
    }

    private function hasId($tag){
        if($tag->id == null){
            $this->push(3, "Campo Id é obrigatório.");
        }
    } 

    private function hasName($tag){
        if($tag->name == null ){
            $this->push(3, "Campo nome é obrigatório");
        }
    } 

}
/* End of file TagValidation.php */
/* Location: ./models/Validation/TagValidation.php */