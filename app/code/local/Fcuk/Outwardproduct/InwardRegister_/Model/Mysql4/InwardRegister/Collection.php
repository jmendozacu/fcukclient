<?php

class Fcuk_InwardRegister_Model_Mysql4_InwardRegister_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('inwardregister/inwardregister');
    }
}