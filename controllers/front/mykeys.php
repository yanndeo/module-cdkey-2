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

include_once(dirname(__FILE__) . '../../../cdkeys.php');

class cdkeysmykeysModuleFrontController extends ModuleFrontController
{
    public $auth = true;
    public function __construct()
    {
        parent::__construct();
        $this->auth = true;
        $link = new Link();
        if (Configuration::get('PS_REWRITING_SETTINGS') == 1)
        {
            $this->authRedirection = $link->getModuleLink('cdkeys', 'mykeys');
        }

        $this->context = Context::getContext();
    }

    public static function getOrderReference($id_order)
    {
        $order = new Order($id_order);
        if (isset($order->reference))
        {
            return $order->reference;
        }
        else
        {
            return '-';
        }
    }

    public static function getOrderDate($id_order)
    {
        $order = new Order($id_order);
        if (isset($order->date_add))
        {
            return $order->date_add;
        }
        else
        {
            return '-';
        }
    }

    public static function getGroupUrl($id_group)
    {
        $CdKeysGroup = new CdKeysGroup($id_group);

        if (isset($CdKeysGroup->url))
        {
            return $CdKeysGroup->url;
        }
        else
        {
            return '-';
        }
    }

    public function initContent()
    {
        $module = new cdkeys();
        $psversion = $module->psversion();
        $cdkeys = CdKeysArchive::getAllByCustomer($this->context->customer->id);
        if (!is_array($cdkeys))
        {
            $cdkeys = array();
        }

        parent::initContent();
        $this->context->smarty->assign(array(
                'cdkeys' => $cdkeys,
                'cdkeys_count' => count($cdkeys),
                'cdkeys_customer' => $this->context->customer,
                'psversion' => $psversion
            ));
        $this->setTemplate('module:cdkeys/views/templates/front/mykeys.tpl');
    }
}