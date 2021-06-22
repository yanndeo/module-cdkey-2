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

require_once _PS_MODULE_DIR_ . 'cdkeys/cdkeys.php';

class AdminCdKeysArchiveController extends ModuleAdminController
{
    protected $position_identifier = 'id_cdkey';

    public function __construct()
    {
        $this->table = 'cdkey_used';
        $this->className = 'CdKeysArchive';
        $this->lang = false;
        parent::__construct();
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->bootstrap = true;
        $this->_orderBy = 'id_cdkey_used';
        $this->_group = 'GROUP BY a.`id_cdkey_used`';

        $this->fields_list = array(
            'id_cdkey_used' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'orderby' => true,
                'width' => 20
            ),
            'code' => array(
                'title' => $this->l('Code'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'id_order' => array(
                'title' => $this->l('Order ID'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'name' => array(
                'title' => $this->l('Group name'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'product' => array(
                'title' => $this->l('Product'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'width' => 50,
                'orderby' => true,
            ),
            'customer_mail' => array(
                'title' => $this->l('Email'),
                'width' => 50,
                'orderby' => true,
            ),
        );
    }

    public function displayEmailLink($token = null, $id, $name = null)
    {
        $Link = new Link();
        $tpl = $this->createTemplate('../../../../modules/cdkeys/views/templates/admin/cdkeys/helpers/list/list_action_email.tpl');
        if (!array_key_exists('Email', self::$cache_lang)) {
            self::$cache_lang['Email'] = $this->l('Send email', 'Helper');
        }

        $tpl->assign(array(
            'href' => $Link->getAdminLink('AdminCdKeysArchive') . '&SendEmail=' . $id . '&token=' . ($token != null ? $token : $this->token),
            'action' => self::$cache_lang['Email'],
            'id' => $id
        ));

        return $tpl->fetch();
    }

    public function renderList()
    {
        $this->initToolbar();
        $this->addRowAction('Email');
        $this->addRowAction('delete');
        $this->addRowAction('edit');
        $this->no_link = true;
        return parent::renderList();
    }

    public function initToolbar()
    {
        if (isset($this->toolbar_btn)) {
            unset($this->toolbar_btn);
        }
        $Link = new Link();
        $this->toolbar_btn['new'] = array(
            'desc' => $this->l('Add new'),
            'href' => $Link->getAdminLink('AdminCdKeysArchive') . '&addcdkey_used'
        );
    }

    public function renderForm()
    {
        if (!$this->loadObject(true)) {
            return;
        }
        $cover = false;
        $obj = $this->loadObject(true);
        if (isset($obj->id)) {
            $this->display = 'edit';
        } else {
            $this->display = 'add';
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('CdKey'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer ID'),
                    'name' => 'id_customer',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Order ID'),
                    'name' => 'id_order',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Code:'),
                    'name' => 'code',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('ID of cdkeys group'),
                    'name' => 'id_cdkey_group',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Group name'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Product'),
                    'name' => 'product',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer'),
                    'name' => 'customer',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer email'),
                    'name' => 'customer_mail',
                    'required' => true,
                    'lang' => false,
                ),

            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        return parent::renderForm();
    }

    public function init()
    {
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = Shop::addSqlRestriction(false, 'a');
        }

        if (Tools::getValue('addcdkey_used', 'false') != 'false') {
            //$this->errors[] = $this->l('It is not possible to add here new entry');
        }
        if (Tools::getValue('id_cdkey_used', 'false') != 'false' && Tools::getValue('deletecdkey_used', 'false') == 'false') {
            //$this->errors[] = $this->l('Changing these details will affect details of code that customer receive');
        }

        if (Tools::getValue('SendEmail', 'false') != 'false') {
            if (is_int((int)Tools::getValue('SendEmail'))) {
                $templateVars = array();
                $code = array();

                $CdKeyArchive = new CdKeysArchive(Tools::getValue('SendEmail'));
                $customer_temp = Customer::getCustomersByEmail($CdKeyArchive->customer_mail);
                $customer = new Customer($customer_temp[0]['id_customer']);
                $order = new Order($CdKeyArchive->id_order);
                $currency = new Currency($order->id_currency);
                $id_shop = $this->context->shop->id;
                $cdkeyGroup = new CdKeysGroup($CdKeyArchive->id_cdkey_group, $order->id_lang, $id_shop);
                $code['code'] = $CdKeyArchive->code;
                $templateVars['{firstname}'] = $customer->firstname;
                $templateVars['{lastname}'] = $customer->lastname;
                $templateVars['{code}'] = $code['code'];
                $templateVars['{product}'] = $CdKeyArchive->product;
                $templateVars['{group_url}'] = $cdkeyGroup->url;
                $templateVars['{group_desc}'] = $cdkeyGroup->group_desc;
                $templateVars['{group_title}'] = $cdkeyGroup->title;
                $templateVars['{product_description}'] = '';
                $templateVars['{product_description_short}'] = '';
                $templateVars['{id_order}'] = $CdKeyArchive->id_order;
                $templateVars['{order_name}'] = $order->getUniqReference();
                $templateVars['{total_paid}'] = Tools::displayPrice($order->total_paid, $currency, false);
                $templateVars['{total_products}'] = Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $order->total_products : $order->total_products_wt, $currency, false);
                $templateVars['{total_discounts}'] = Tools::displayPrice($order->total_discounts, $currency, false);
                $templateVars['{total_shipping}'] = Tools::displayPrice($order->total_shipping, $currency, false);
                $templateVars['{total_shipping_tax_excl}'] = Tools::displayPrice($order->total_shipping_tax_excl, $currency, false);
                $templateVars['{total_shipping_tax_incl}'] = Tools::displayPrice($order->total_shipping_tax_incl, $currency, false);
                $templateVars['{total_wrapping}'] = Tools::displayPrice($order->total_wrapping, $currency, false);
                $templateVars['{total_tax_paid}'] = Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $currency, false);
                $templateVars['{payment}'] = Tools::substr($order->payment, 0, 255);

                $cdkeys_module = Module::getInstanceByName('cdkeys');
                $templateVars['{group_desc}'] = $cdkeys_module->replaceMailTitle($cdkeyGroup->group_desc, $templateVars);

                if (Mail::Send($order->id_lang, $cdkeyGroup->template, $cdkeys_module->replaceMailTitle($cdkeyGroup->title, $templateVars), $templateVars, strval($customer->email), null, strval(Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop)), strval(Configuration::get('PS_SHOP_NAME', null, null, $id_shop)), null, null, dirname(__file__) . '../../../mails/', false, $id_shop)) {
                    $this->confirmations[] = $this->l('Email sent properly');
                } else {
                    $this->errors[] = $this->l('Errors with email delivery');
                }
            } else {
                $this->errors[] = $this->l('Errors with email delivery');
            }
        }

        parent::init();
    }

    public function beforeAdd($object)
    {
        $object->id_shop = Context::getContext()->shop->id;
        return true;
    }

    public function processUpdate()
    {
        $object = parent::processUpdate();
        return true;
    }

    public function postProcess()
    {
        return parent::postProcess();
    }
}