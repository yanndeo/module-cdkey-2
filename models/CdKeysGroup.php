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
class CdKeysGroup extends ObjectModel
{
    public $id_cdkey_group;
    public $id_product;
    public $name;
    public $url;
    public $invoice_name;
    public $id_shop;
    public $av;
    public $us;
    public $hm;
    public $for_attr;
    public $id_product_attribute;
    public $syncstock;
    public $template = 'cdkey';
    public $title;
    public $group_desc;
    public static $definition = array(
        'table' => 'cdkey_group',
        'primary' => 'id_cdkey_group',
        'multilang' => true,
        'fields' => array(
            'id_cdkey_group' => array('type' => ObjectModel :: TYPE_INT),
            'id_shop' => array('type' => ObjectModel :: TYPE_INT),
            'id_product' => array('type' => ObjectModel :: TYPE_INT),
            'id_product_attribute' => array('type' => ObjectModel :: TYPE_INT),
            'for_attr' => array('type' => ObjectModel:: TYPE_INT),
            'name' => array('type' => ObjectModel :: TYPE_STRING),
            'url' => array('type' => ObjectModel :: TYPE_STRING),
            'template' => array('type' => ObjectModel :: TYPE_STRING),
            'title' => array('type' => ObjectModel :: TYPE_STRING, 'lang' => true),
            'invoice_name' => array('type' => ObjectModel :: TYPE_STRING),
            'av' => array('type' => ObjectModel:: TYPE_INT),
            'us' => array('type' => ObjectModel:: TYPE_INT),
            'hm' => array('type' => ObjectModel:: TYPE_INT),
            'syncstock' => array('type' => ObjectModel:: TYPE_INT),
            'group_desc' => array('lang' => true, 'type' => ObjectModel:: TYPE_NOTHING),
        ),
    );

    public static function getAll()
    {
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $id_shop = Context::getContext()->shop->id;
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_group` WHERE id_shop=' . $id_shop);
        } else {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_group`');
        }
    }

    public static function getById($id)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_group` WHERE `id_cdkey_group`=' . $id);
    }

}