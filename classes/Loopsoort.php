<?php
/**
 * Created by PhpStorm.
 * User: niek
 * Date: 8-6-2015
 * Time: 09:34
 */

class Loopsoort   {
    private $_db;

    public function __construct(){
        $this->_db = Db::getInstance();
    }

    public function getAllloopsoort(){
        $this->_db->getAll('loopsoorten');
        return $this->_db->results();
    }
}

?>