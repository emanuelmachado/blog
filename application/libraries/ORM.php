<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ORM {

    protected $CI;
    public $table = "";
    public $entity = "";
    public $errors = "";
    public $primary_key = "id";
    public $tableAlias = "";

    public function __construct() {
        $this->CI = & get_instance();
        
    }

    public function initialize($table, $entity, $primary_key = null, $tableAlias = null){
        $this->entity = $entity;
        $this->table = $table;
        $this->tableAlias = $tableAlias;

        if(!is_null($primary_key)){
            $this->primary_key = $primary_key;
        }
        $this->validateTables();
    }

    public function save($object) {
        if (!empty($this->errors)) {
            return false;
        }
        if ($object->{$this->primary_key} != null) {
            return $this->edit($object);
        } else {
            return $this->add($object);
        }
    }

    public function add($object) {
        if (!empty($this->errors)) {
            return false;
        }
        $object->insertionDate = date('Y-m-d H:i:s');
        $object->insertionUser  = $object->insertionUser != null ? $object->insertionUser : $this->CI->session->userdata("user_id");

        $this->CI->db->insert($this->table, $object);
        if ($this->CI->db->affected_rows() == 1) {
            $object->{$this->primary_key} = $this->CI->db->insert_id();
        }
        return $object;
    }

    public function edit($object) {
        if (!empty($this->errors)) {
            return false;
        }
        $object->updatingDate = date('Y-m-d H:i:s');
        $object->updatingUser = $object->updatingUser != null ? $object->updatingUser : $this->CI->session->userdata("user_id");

        $this->CI->db->where($this->primary_key, $object->{$this->primary_key});
        $this->CI->db->update($this->table, $object);

        return $this->getById($object->id);
    }
    
    public function get($object) {
        if (!empty($this->errors)) {
            return false;
        }

        $this->CI->db->where($this->primary_key, $object->{$this->primary_key});
        $query = $this->CI->db->get($this->table);
        return $query->row(0, $this->entity);
    }
    
    public function getById($id) {
        if ($id == null) {
            return null;
        }

        $object = new $this->entity;
        $object->{$this->primary_key} = (int)$id;

        return $this->get($object);
    }

    public function getAll(){
        $query = $this->CI->db->get($this->table);

        return  ($query != null && $query->num_rows() > 0) ? $query->result($this->entity) : array();
    }

    public function filter($fields = null,$arrWhere = null,$arrLike = null, $orderBy = null,$limit = null,$offset = null, $innerJoins = null){
        if(!empty($this->errors)){
            return false;
        }

        $this->addConditionFields($fields);
        $this->addConditionWhere($arrWhere);
        $this->addConditionLike($arrLike);
        $this->addLimitAndOffset($limit,$offset);
        $this->addOrderBy($orderBy);
        $this->addInnerJoin($innerJoins);

        $query = $this->CI->db->get("{$this->table} {$this->tableAlias}");
        return ($query != null && $query->num_rows() > 0) ? $query->result() : array();
    }
    
    public function getByQuery($query, $entity = null) {
        if (!empty($this->errors)) {
            $items = false;
        }
        $items = array();
        
        $query = $this->CI->db->query($query);
        if ($query != null && $query->num_rows() > 0) {
            $isSingular = $query->num_rows() == 1;
            if($entity == null){
                $items = $isSingular ? $query->row(0, $this->entity) : $query->result($this->entity);
            }else if($entity === 'none'){
                $items = $isSingular ? $query->row(0) : $query->result();
            }else{
                $items = $isSingular ? $query->row(0, $entity) : $query->result($entity);
            }
        } 
        return $items;
    }

    public function delete($id){
        if (!empty($this->errors)) {
            return false;
        }
        $this->CI->db->where($this->primary_key, $id);
        $this->CI->db->delete($this->table);
        return ($this->CI->db->affected_rows() == 1);
    }

    public function logicalRemoval($id){
        if (!empty($this->errors)) {
            return false;
        }
        $update = "UPDATE $this->table t
                SET t.status = false
                WHERE id = $id";
        
       return $this->CI->db->simple_query($update);
       
    }

    public function getByIdIn($ids){
        $query = "SELECT * 
        FROM  $this->table
        WHERE s.id IN (".join(',',$ids).")";
        return $this->CI->db->simple_query($query);
    }

    public function getByIdNotIn($ids){
        $query = "SELECT * 
        FROM  $this->table
        WHERE s.id NOT IN (".join(',',$ids).")";
        return $this->CI->db->simple_query($query);
    }

    public function countAll(){
        return $this->CI->db->count_all("{$this->table} {$this->tableAlias}");
    }

    public function countFilter($arrWhere, $innerJoins = null){
        $this->CI->db->where($arrWhere);
        $this->CI->db->from("{$this->table} {$this->tableAlias}");
        $this->addInnerJoin($innerJoins);
        return $this->CI->db->count_all_results();
    }


    public function addBatch($array){
        if (!empty($this->errors)) {
            return false;
        }
        $this->CI->db->insert_batch($this->table, $array);

        return ($this->CI->db->affected_rows() > 0);
    }

    private function validateTables() {
        if (!$this->CI->db->table_exists($this->table)) {
            $this->errors .= "There isn't table " . ucfirst($this->table) . " in schema.";
            log_message("error", $this->errors, true);
        }
    }

    //Auxiliaries Methods getAll 
    private function addConditionFields($fields){
        if($fields == null || $fields == "*") {
            $fields = $this->CI->db->list_fields($this->table);
            $this->CI->db->select(implode(",",$fields));
        }
    }

    private function addConditionWhere($where){
        if($where != null){
            $this->CI->db->where($where);
        }
    }

    private function addConditionLike($arrLike){
        if(isset($arrLike) === TRUE && is_array($arrLike)){
            foreach($arrLike as $like){
                if($like->operator == "or"){
                    $this->CI->db->or_like($like->field, $like->match, $like->option);
                }else{
                    $this->CI->db->like($like->field, $like->match, $like->option);
                }
            }
        }
    }

    private function addLimitAndOffset($limit, $offset){
        if(isset($limit) === TRUE){
            if($offset != null){
                $this->CI->db->limit($limit,$offset);
            }else{
                $this->CI->db->limit($limit);
            }
        }
    }

    private function addOrderBy($orderBy){
        if(isset($orderBy) === TRUE){
            $this->CI->db->order_by($orderBy);
        }
    }

    private function addInnerJoin($innerJoins){
        if(isset($innerJoins) === TRUE && is_array($innerJoins)){
            foreach($innerJoins as $innerJoin){
                $this->CI->db->join($innerJoin["table"], $innerJoin["condition"]);
            }
        }
    }

}
/* End of file ORM.php */
/* Location: ./libraries/ORM.php */