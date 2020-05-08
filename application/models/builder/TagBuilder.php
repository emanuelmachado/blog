<?php defined('BASEPATH') || exit('No direct script access allowed');

class TagBuilder{

    public function build($name){
        $objTag = new TagEntity;

        $objTag->name = $name;

        return $objTag;
    }

    public function buildFull($objTag, $name){

        $objTag->name = $name;

        return $objTag;
    }
}