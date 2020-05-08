<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultService {

    protected $CI;
    protected $entity;
    protected $dal;

    public function save($entity){
        return $this->dal->save($entity);
    }

    public function getById($id) {
        return $this->dal->getById($id);
    }

    public function getAll(){
        return $this->dal->getAll();
    }

    public function filter($fields = null,$arrWhere = null, $like = null,$orderBy = null,$limit = null,$offset = null) {
        return $this->dal->filter($fields,$arrWhere,$like,$orderBy,$limit,$offset);
    }

    public function countAll(){
        return $this->dal->countAll();
    }

    public function countFilter($search){
        return $this->dal->countFilter($search);
    }

    public function delete($id){
        return $this->dal->delete($id); 
    }

    public function list($search, $limit = null,$offset = null){
        return $this->dal->list($search, $limit ,$offset);
    }

    public function getByIdIn($ids){
        return $this->dal->getByIdIn($ids);
    }

    public function getByIdNotIn($ids){
        return $this->dal->getByIdNotIn($ids);
    }
}

/* End of file DefaultService.php */
/* Location: ./models/Service/DefaultService.php */
