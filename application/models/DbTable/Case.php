<?php

class Application_Model_DbTable_Case extends Zend_Db_Table_Abstract
{
    protected $_name = 'cases';

    public function getCaseByNumber($number)
    {
        $select = $this->select();
        $select
            ->where('number = ?', $number);

        $caseArray =  $this->fetchAll($select)->toArray();
        return $caseArray[0];
    }

    public function getCaseById($id)
    {
        $select = $this->select();
        $select
            ->where('id = ?', $id);
        $caseArray = $this->fetchAll($select)->toArray();
        return $caseArray[0];
    }

    public function updateCaseByNumber($number, $data)
    {
        $where = $this->getAdapter()->quoteInto('number = ?', $number);
        $this->update($data, $where);
    }
    public function updateCaseById($id, $data)
    {
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $this->update($data, $where);
    }

    public function getCasesByType ($type = 1, $archive = 0)
    {
        $select = $this->select();

        $select->where('type = ?', $type)
                ->where('archive = ?', $archive);
        return $this->fetchAll($select)->toArray();
    }

    public function getArchivedCases()
    {
        $select = $this->select();
        $select->where('archive = 1');

        return $this->fetchAll($select)->toArray();
    }
}