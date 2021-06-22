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

class AdminCdKeysListController extends ModuleAdminController
{
    protected $position_identifier = 'id_cdkey';

    public function __construct()
    {
        $this->table = 'cdkey';
        $this->className = 'CdKeysList';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->bootstrap = true;
        $this->_orderBy = 'id_cdkey';
        $this->_group = 'GROUP BY a.`id_cdkey`';

        $this->fields_list = array(
            'id_cdkey' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'orderby' => true,
                'width' => 20
            ),
            'id_cdkey_group' => array(
                'title' => $this->l('Group'),
                'width' => 'auto',
                'orderby' => true,
                //'desc' => $this->l('read').'<a href="">'.$this->l('how to get Product ID').'</a>',
                'callback' => 'getGroupName',
                'filter_key' => 'b!name',
            ),
            'code' => array(
                'title' => $this->l('Code'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'width' => 50,
                'orderby' => true,
                'type' => 'bool',
                'active' => 'status',
            ),
        );

        $this->_join = 'LEFT JOIN `' . _DB_PREFIX_ . 'cdkey_group` b ON (b.`id_cdkey_group` = a.`id_cdkey_group`)';
    }

    public function renderList()
    {
        $this->initToolbar();
        return parent::renderList();
    }

    public function getGroupName($group, $row)
    {
        $group = new CdKeysGroup($row['id_cdkey_group']);
        return $group->name;
    }

    public function init()
    {
        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive())
        {
            $this->_where = 'AND b.id_shop=' . Context::getContext()->shop->id;
        }
        parent::init();
    }

    public function initToolbar()
    {
        unset($this->toolbar_btn);
        $Link = new Link();
        $this->toolbar_btn['new'] = array(
            'desc' => $this->l('Add new'),
            'href' => $Link->getAdminLink('AdminCdKeysList') . '&addcdkey'
        );
        $this->toolbar_btn['import'] = array(
            'desc' => $this->l('Import codes'),
            'href' => $Link->getAdminLink('AdminModules') . '&configure=cdkeys'
        );
        $this->toolbar_btn['dollar'] = array(
            'desc' => $this->l('Sold codes'),
            'href' => $Link->getAdminLink('AdminCdKeysArchive')
        );
    }

    public function initFormToolBar()
    {
    }

    public function renderForm()
    {
        $this->initFormToolBar();
        if (!$this->loadObject(true))
        {
            return;
        }
        $cover = false;
        $obj = $this->loadObject(true);
        if (isset($obj->id))
        {
            $this->display = 'edit';
        } else
        {
            $this->display = 'add';
        }
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('CdKey'),
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Code:'),
                    'name' => 'code',
                    'required' => true,
                    'lang' => false,
                    'desc' => $this->l('Add one code or list of codes (each code in new line like example below)').'<br/>2DFE-QD443-VTD96-BJQYK-7XMP2<br/>12D32-QN3VT-XP4XT-KTY4V-MBH22<br/>5GF22-NV2YT-QVPMF-8HYD7-JQKTP<br/>XYAS4-W6PC7-BJRFW-42M4Q-GF4C2<br/>',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Group'),
                    'name' => 'id_cdkey_group',
                    'required' => true,
                    'lang' => false,
                    'options' => array(
                        'query' => Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_group` WHERE 1 ' . (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() ? Shop::addSqlRestriction(false) : '') . ' ORDER BY name ASC'),
                        'id' => 'id_cdkey_group',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active:'),
                    'name' => 'active',
                    'required' => true,
                    'lang' => false,
                    'values' => array(
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
                ),

            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        return parent::renderForm();
    }

    public function processAdd()
    {
        foreach (explode("\n", $_POST['code']) as $code)
        {
            if (strlen($code) > 0 && trim($code) != '')
            {
                $cdkey = new CdKeysList();
                $cdkey->code = trim($code);
                $cdkey->active = Tools::getValue('active');
                $cdkey->id_cdkey_group = Tools::getValue('id_cdkey_group');
                $cdkey->add();
            }
        }
        //$object = parent::processAdd();
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