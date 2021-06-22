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
class CdKeysList extends ObjectModel
{
    public $id_cdkey;
    public $id_cdkey_group;
    public $code;
    public $active;
    public static $definition = array(
        'table' => 'cdkey',
        'primary' => 'id_cdkey',
        'multilang' => false,
        'fields' => array(
            'id_cdkey' => array('type' => ObjectModel :: TYPE_INT),
            'id_cdkey_group' => array('type' => ObjectModel :: TYPE_INT),
            'code' => array('type' => ObjectModel :: TYPE_STRING),
            'active' => array('type' => ObjectModel :: TYPE_INT),
        ),
    );
    public function delete()
    {
        $return = parent::delete();
        $this->syncStock();
        return $return;
    }

    public function update($null_values = false)
    {
        $return = parent::update($null_values);
        $this->syncStock();
        return $return;
    }

    public function add($auto_date = true, $null_values = false)
    {
        if (Configuration::get('cdkey_prevdup') == true && $this->getByCdKey($this->code) == true)
        {
            Context::getContext()->controller->warnings[] = $this->code;
        }
        else
        {
            $return = parent::add($auto_date = true, $null_values = false);
            $this->syncStock();
            return $return;
        }
    }

    public function syncStock()
    {
        $cdkeysgroup = new CdKeysGroup($this->id_cdkey_group);
        if ($cdkeysgroup->syncstock == 1)
        {
            StockAvailable::setQuantity((int)$cdkeysgroup->id_product, (int)$cdkeysgroup->id_product_attribute, $this->getAvKeys(null, $this->id_cdkey_group), (int)Context::getContext()->shop->id);
        }
    }

    public function getAvKeys($product, $row)
    {
        $return = Db::getInstance()->getRow("SELECT count(*) AS counter FROM `" . _DB_PREFIX_ . 'cdkey`' . " WHERE id_cdkey_group=" . $row);
        if (isset($return['counter']))
        {
            return $return['counter'];
        }
        else
        {
            return 0;
        }
    }

    public function getByCdKey($cdkey)
    {
        if ($cdkey == '' || $cdkey == null || $cdkey == false)
        {
            return false;
        }
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey` WHERE code="' . $cdkey . '"');

        if ($record == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function getOne($id_group)
    {
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey` where id_cdkey_group="' . $id_group . '" AND active=1');
        return $record;
    }

    public function __construct($id_cdkey = null)
    {
        parent::__construct($id_cdkey);
    }
}