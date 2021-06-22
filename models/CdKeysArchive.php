<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class CdKeysArchive extends ObjectModel
{
    public $id_cdkey_used;
    public $id_order;
    public $code;
    public $id_cdkey_group;
    public $name;
    public $product;
    public $customer;
    public $customer_mail;
    public $id_customer;
    public $id_shop;
    public $id_order_detail;
    public static $definition = array(
        'table' => 'cdkey_used',
        'primary' => 'id_cdkey_used',
        'multilang' => false,
        'fields' => array(
            'id_cdkey_used' => array('type' => ObjectModel :: TYPE_INT),
            'id_shop' => array('type' => ObjectModel :: TYPE_INT),
            'id_order_detail' => array('type' => ObjectModel :: TYPE_INT),
            'id_customer' => array('type' => ObjectModel :: TYPE_INT),
            'id_order' => array('type' => ObjectModel :: TYPE_INT),
            'id_cdkey_group' => array('type' => ObjectModel :: TYPE_INT),
            'code' => array('type' => ObjectModel :: TYPE_STRING),
            'name' => array('type' => ObjectModel :: TYPE_STRING),
            'product' => array('type' => ObjectModel :: TYPE_STRING),
            'customer' => array('type' => ObjectModel :: TYPE_STRING),
            'customer_mail' => array('type' => ObjectModel :: TYPE_STRING),
        ),
    );

    public static function getAllByIdOrder($id_order)
    {
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_used` WHERE id_order="' . $id_order . '"');
        if (count($record) <= 0)
        {
            return false;
        }
        else
        {
            return $record;
        }
    }

    public static function getAllByCustomer($id_customer)
    {
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_used` WHERE id_customer="' . $id_customer . '"');
        return $record;
    }

    public static function verifyIfExists($id_customer, $id_order)
    {
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_used` WHERE id_customer="' . $id_customer . '" AND id_order="' . $id_order . '"');
        return $record;
    }

    public function __construct($id_cdkey_used = null)
    {
        parent::__construct($id_cdkey_used);
    }
}