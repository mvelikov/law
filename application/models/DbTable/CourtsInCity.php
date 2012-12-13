<?php

class Application_Model_DbTable_CourtsInCity extends Zend_Db_Table_Abstract {

    protected $_name = 'courts_in_cities';

    public function selectAllCourtsInCity($cityId) {
        $query = $this->select();
        $query->setIntegrityCheck(false)
                ->from(array('c' => 'courts_in_cities'), array())
                ->join(array('ct' => 'cities'), 'c.city_id = ct.id', array())
                ->join(array('cr' => 'courts'), 'c.court_id = cr.id', array())
                ->where('cr.id = ?', $cityId);
        $resultRows = $this->fetchAll($query);
        echo $query->__toString();
        return $resultRows;
    }

}