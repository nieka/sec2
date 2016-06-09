<?php
/**
 * Created by PhpStorm.
 * User: niek
 * Date: 8-4-2015
 * Time: 11:40
 */
class Regio {
    private $_db;

    public function __construct(){
        $this->_db = Db::getInstance();
    }

    public function getAllregios(){
        $this->_db->getAll('regio');
        return $this->_db->results();
    }

    public function makeregio($tag){
        if($this->_db->insert('regio',array(
            'Tag'   => $tag
        ))){
            return true;
        } else {
            return false;
        }
    }

    public function createTRegio($regio){
        if($this->_db->insert('regio',array(
            'Regio'    =>  $regio
        ))) {
            return true;
        }else{
            return false;
        }

    }

    public function deleteTag($regio){
        if($this->_db->delete('regio',array('Regio','=',$regio))) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegioD($regio){
        if($this->_db->get('regio',array('Regio','=',$regio))){
            return $this->_db->first();
        }
    }

}

?>