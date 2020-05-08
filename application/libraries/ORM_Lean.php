<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ORM_Lean extends ORM {

    public function add($object) {
        if (!empty($this->errors)) {
            return false;
        }

        $this->CI->db->insert($this->table, $object);

        $object->{$this->primary_key} = ($this->CI->db->affected_rows() == 1) ? $this->CI->db->insert_id() : null;

        return $object;
    }

    public function edit($object) {
        if (!empty($this->errors)) {
            return false;
        }
        $this->CI->db->where($this->primary_key, $object->{$this->primary_key});
        $this->CI->db->update($this->table, $object);

        return $this->CI->db->affected_rows() == 1;
    }

    public function delete($id){
        if (!empty($this->errors)) {
            return false;
        }
        $this->CI->db->where($this->primary_key, $id);
        $this->CI->db->delete($this->table);
        return ($this->CI->db->affected_rows() == 1);
    }
}

/* End of file ORM_Lean.php */
/* Location: ./libraries/ORM_Lean.php */