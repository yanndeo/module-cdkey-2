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
if (file_exists(_PS_MODULE_DIR_ . 'cdkeys/lib/searchTool/searchTool.php')) {
    require_once _PS_MODULE_DIR_ . 'cdkeys/lib/searchTool/searchTool.php';
}

class AdminCdKeysGroupsController extends ModuleAdminController
{
    protected $position_identifier = 'id_cdkey_group';

    public function __construct()
    {
        $this->table = 'cdkey_group';
        $this->className = 'CdKeysGroup';
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );

        $this->searchTool = new searchToolcdkeys('cdkeys', 'other');
        $this->module->emailTemplatesManager = new cdkeysEmailTemplatesManager($this->module->name, $this->module->availableTemplateVars);
        $this->bootstrap = true;
        $this->_orderBy = 'id_cdkey_group';
        $this->_group = 'GROUP BY a.`id_cdkey_group`';

        $this->fields_list = array(
            'id_cdkey_group' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 'auto',
                'orderby' => true,
                'filter_key' => 'a!name',
            ),
            'id_product' => array(
                'title' => $this->l('Product'),
                'orderby' => true,
                //'desc' => $this->l('read').'<a href="">'.$this->l('how to get Product ID').'</a>',
                'callback' => 'getProductName'
            ),
            'id_product_attribute' => array(
                'title' => $this->l('Combination'),
                'orderby' => true,
                //'desc' => $this->l('read').'<a href="">'.$this->l('how to get Product ID').'</a>',
                'callback' => 'getProductAttribute'
            ),
            'hm' => array(
                'title' => $this->l('How many keys to deliver?'),
                'orderby' => false,
            ),
            'av' => array(
                'title' => $this->l('Available keys'),
                'orderby' => false,
                'callback' => 'getAvKeys',
            ),
            'us' => array(
                'title' => $this->l('Used Keys'),
                'orderby' => false,
                'callback' => 'getUsedKeys',
            ),
            'syncstock' => array(
                'title' => $this->l('Sync stock'),
                'orderby' => true,
                'type' => 'bool',
                'active' => 'syncstock',
                'ajax' => true,
            ),
            'title' => array(
                'title' => $this->l('Email title'),
                'type' => 'text',
            ),
            'template' => array(
                'title' => $this->l('Email template'),
                'orderby' => true,
                'type' => 'text',
            ),
            'url' => array(
                'title' => $this->l('URL'),
                'width' => 'auto'
            ),
            /**
             * 'mail_additional_info' => array(
             * 'title' => $this->l('Additional info'),
             * 'width' => 'auto'
             * )
             **/
        );
    }

    public function getProductAttribute($combination, $row)
    {
        if ($combination == 0) {
            return '-';
        } else {
            $cb = new Combination($combination, $this->context->language->id);
            $cb_name = $cb->getAttributesName($this->context->language->id);
            $combination_name = '';
            if (count($cb_name) > 0) {
                foreach ($cb_name AS $cb_name) {
                    $combination_name .= $cb_name['name'] . ' ';
                }
            } else {
                $combination_name = '-';
            }
            return $combination_name;
        }
    }

    public function renderList()
    {
        $this->initToolbar();
        return parent::renderList();
    }

    public function getAvKeys($product, $row)
    {
        $return = Db::getInstance()->getRow("SELECT count(*) AS counter FROM `" . _DB_PREFIX_ . 'cdkey`' . " WHERE id_cdkey_group=" . $row['id_cdkey_group']);
        if (isset($return['counter'])) {
            return $return['counter'];
        } else {
            return 0;
        }
    }

    public function getUsedKeys($product, $row)
    {
        $groupFromDb = CdKeysGroup::getById($row['id_cdkey_group']);
        if (isset($groupFromDb[0]['name'])) {
            $where_name = '`name` ="' . pSQL($groupFromDb[0]['name']) . '" OR ';
        } else {
            $where_name = '';
        }

        $return = Db::getInstance()->getRow("SELECT count(*) AS counter FROM " . _DB_PREFIX_ . 'cdkey_used' . " WHERE " . $where_name . " `id_cdkey_group`=" . $row['id_cdkey_group']);
        if (isset($return['counter'])) {
            return $return['counter'];
        } else {
            return 0;
        }
    }

    public function getProductName($product_id, $row)
    {
        $product = new Product($product_id, false, $this->context->language->id);
        return '#' . $product_id . ' ' . $product->name;
    }

    public function init()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryPlugin('autocomplete');
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = Shop::addSqlRestriction(false, 'a');
        }
        parent::init();
    }

    public function initFormToolBar()
    {
    }

    public function renderForm()
    {
        $this->initFormToolBar();
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

        $options = array(
            array(
                'id_option' => '0',
                'name' => $this->l('No')
            ),
            array(
                'id_option' => '1',
                'name' => $this->l('Yes')
            )
        );

        $cdkeys_module = Module::getInstanceByName('cdkeys');

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Groups of CdKeys'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Internal Name:'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Invoice Name:'),
                    'name' => 'invoice_name',
                    'required' => true,
                    'lang' => false,
                    'desc' => $this->l('Invoice name will appear near key on invoice, something like:') . ' <strong>' . $this->l('Invoice name:') . 'XXXX-XXXX-XXXX-XXXX</strong>',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Product:'),
                    'name' => 'id_product',
                    'desc' => $this->l('Enter ID of product. When someone will order it module will send CdKey code') . ' ' . $this->l('You can also search for product by its name') . ' ' . $this->searchTool->searchTool('product', 'id_product', 'replace', true, $obj->id_product),
                    'required' => true,
                    'lang' => false,
                    'prefix' => $this->searchTool->searchTool('product', 'id_product', 'replace'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('For specific combination'),
                    'name' => 'for_attr',
                    'desc' => $this->l('Turn this option on if you want to send cdkey associated with this group only if customer will order specific combination of product'),
                    'required' => true,
                    'options' => array(
                        'query' => $options,
                        'id' => 'id_option',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('How many keys to deliver?'),
                    'name' => 'hm',
                    'desc' => $this->l('This field decides how many cdkeys module will deliver to customer after purchase of 1 qantity of this product'),
                    'required' => true,
                    'lang' => false,
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Combination'),
                    'name' => 'id_product_attribute',
                    'html_content' => $this->getCombinationsForm((isset($this->object) ? $this->object : false)),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Sync stock'),
                    'name' => 'syncstock',
                    'required' => true,
                    'lang' => false,
                    'desc' => $this->l('Option when active will update stock of product (or combination). It will make product\'s stock equal to the number of codes in database.') . '<br/>'
                        . '<div class="alert alert-warning">' . $this->l('Module updates the stock immediately, so if you will activate this option the stock of product (or combination) associated with this group of cdkeys will be updated.') . '<br/>' . $this->l('If you want to manually update the stock just save settings for this group with active option "sync stock"') . '</div>',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('On')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Off')
                            )
                        ),
                        'id' => 'value',
                        'name' => 'label'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Email template'),
                    'name' => 'template',
                    'class' => "emailTemplateManager_selectBox",
                    'options' => array(
                        'query' => $this->getMailFiles(),
                        'id' => 'name',
                        'name' => 'name'
                    ),
                    'desc' => (Configuration::get('CDKEY_HOWDELIVER') == 1 ? ('<div class="alert alert-danger">' . $this->l('You use option to send various keys in one email. If your customer placed an order for several cdkeys in one cart - module will use "multikeymail" template file to build email contents even if you will select here different option') . '</div>') : '') . $this->l('Select email template file (located in module mails/en directory)') . '<br/><br/>' . $this->module->emailTemplatesManager->generateEmailTemplatesManagerButton(),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Email title'),
                    'name' => 'title',
                    'required' => true,
                    'lang' => true,
                    'desc' => $this->l('This will be the title of the email that customer will receive when module will send and mail with cdkey to customer.') . '<br/>' . $this->l('You can use these variables in email title: ') . '<br/>' . implode("<br/>", array_map(function ($v, $k) {return $k.':'.$v;}, $cdkeys_module->availableTemplateVars, array_keys($cdkeys_module->availableTemplateVars)))
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Additional email informations'),
                    'name' => 'group_desc',
                    'required' => true,
                    'lang' => true,
                    'autoload_rte' => true,
                    'desc' => $this->l('Module will include these contents to the email delivered to customer') . '<br/>' . $this->l('You can use these variables in email additional informations: ') . '<br/>' . implode("<br/>", array_map(function ($v, $k) {return $k.':'.$v;}, $cdkeys_module->availableTemplateVars, array_keys($cdkeys_module->availableTemplateVars)))
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Url:'),
                    'name' => 'url',
                    'required' => true,
                    'lang' => false,
                    'desc' => $this->l('this is an optional parameter, you can insert here url that will be included to email that customer will receive or that will be visible in "my cdkeys" section')
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        return parent::renderForm() . $this->returnScript() . $this->searchTool->initTool();
    }

    public function getCombinationsForm($object)
    {
        if (isset($object->id_product_attribute)) {
            $this->context->smarty->assign('id_product_attribute', $object->id_product_attribute);
        } else {
            $this->context->smarty->assign('id_product_attribute', 0);
        }
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'cdkeys/views/templates/admin/getCombinations.tpl');
    }

    public function returnScript()
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'cdkeys/views/templates/admin/script.tpl');
    }

    public function beforeAdd($object)
    {
        $object->id_shop = Context::getContext()->shop->id;
        return true;
    }

    public function processAdd()
    {
        $object = parent::processAdd();
        if ($object->syncstock == 1) {
            $row['id_cdkey_group'] = $object->id_cdkey_group;
            StockAvailable::setQuantity((int)$object->id_product, (int)$object->id_product_attribute, $this->getAvKeys(null, $row), (int)$this->context->shop->id);
        }
        return true;
    }

    public function processUpdate()
    {
        $object = parent::processUpdate();
        if ($object->syncstock == 1) {
            $row['id_cdkey_group'] = $object->id_cdkey_group;
            StockAvailable::setQuantity((int)$object->id_product, (int)$object->id_product_attribute, $this->getAvKeys(null, $row), (int)$this->context->shop->id);
        }
    }

    public function postProcess()
    {
        return parent::postProcess();
    }

    public function ajaxProcess()
    {
        if (Tools::isSubmit('id_cdkey_group') && Tools::isSubmit('ajax') && Tools::isSubmit('syncstockcdkey_group')) {
            $group = new CdKeysGroup(Tools::getValue('id_cdkey_group'));
            $status_before = $group->syncstock;
            $group->syncstock = !(int)$group->syncstock;
            if ($group->update(false)) {
                $success['success'] = true;
                $success['text'] = $this->l('Saved');
                echo Tools::jsonEncode($success);
                if ($status_before == 0) {
                    StockAvailable::setQuantity((int)$group->id_product, (int)$group->id_product_attribute, $this->getAvKeys(null, $group->id_cdkey_group), (int)$this->context->shop->id);
                }
            }
        }

        if (Tools::isSubmit('idp') && Tools::isSubmit('combinations')) {
            $product = new Product(Tools::getValue('idp'), true, $this->context->language->id);
            $combinations = $product->getAttributeCombinations($this->context->language->id);

            if (count($combinations > 0)) {
                foreach ($combinations AS $key => $combination) {
                    $cb = new Combination($combination['id_product_attribute'], $this->context->language->id);
                    $cb_name = $cb->getAttributesName($this->context->language->id);
                    $combination_name = '';
                    if (count($cb_name) > 0) {
                        foreach ($cb_name AS $cb_name) {
                            $combination_name .= $cb_name['name'] . ' ';
                        }
                    }
                    echo "<div class='form-control-static margin-form'><div class='btn btn-default' onclick=\"$('.id_product_attribute').val(" . $combination['id_product_attribute'] . ")\" >" . $this->l('select') . "</div> " . $combination_name . " " . ($combination['reference'] != '' ? '(' . $combination['reference'] . ')' : '') . "</div>";
                }
            }
        }
    }

    public function getMailFiles()
    {
        $dir = "../modules/cdkeys/mails/en/";
        $dh = opendir($dir);
        while (false !== ($filename = readdir($dh))) {
            if ($filename != ".." && $filename != "." && $filename != "" && $filename != "index.php") {
                $explode = explode(".", $filename);
                $files[$explode[0]]['name'] = $explode[0];
            }
        }
        return $files;
    }

}