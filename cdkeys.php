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

require_once _PS_MODULE_DIR_ . 'cdkeys/models/CdKeysGroup.php';
require_once _PS_MODULE_DIR_ . 'cdkeys/models/CdKeysList.php';
require_once _PS_MODULE_DIR_ . 'cdkeys/models/CdKeysArchive.php';

class cdkeys extends Module
{
    public $availableTemplateVars;

    function __construct()
    {
        $this->name = 'cdkeys';
        $this->tab = 'advertising_marketing';
        $this->author = 'MyPresta.eu';
        $this->version = '2.3.2';
        $this->module_key = '1abb6178d31e1832f0a64e1b4e3bc62a';
        parent::__construct();
        $this->bootstrap = true;
        $this->dir = '/modules/cdkeys/';
        $this->mypresta_link = 'https://mypresta.eu/modules/ordering-process/sell-cdkeys-license-keys.html';
        $this->displayName = $this->l('Cd-Keys / license module');
        $this->description = $this->l('With this module you can sell cd-keys / license codes. Addon will send cd keys to customers right after order of the cd-key/license product.');
        $this->addproduct = $this->l('Add');
        $this->noproductsfound = $this->l('No products found');
        $this->checkforupdates();
        $this->availableTemplateVars['{firstname}'] = '';
        $this->availableTemplateVars['{lastname}'] = '';
        $this->availableTemplateVars['{code}'] = '';
        //add
        $this->availableTemplateVars['{cdkeypwd}'] = '';
        $this->availableTemplateVars['{product}'] = '';
        $this->availableTemplateVars['{product_description}'] = '';
        $this->availableTemplateVars['{product_description_short}'] = '';
        $this->availableTemplateVars['{group_url}'] = '';
        $this->availableTemplateVars['{group_desc}'] = '';
        $this->availableTemplateVars['{group_title}'] = '';
        $this->availableTemplateVars['{id_order}'] = '';
        $this->availableTemplateVars['{order_name}'] = '';
        $this->availableTemplateVars['{total_paid}'] = '';
        $this->availableTemplateVars['{total_products}'] = '';
        $this->availableTemplateVars['{total_discounts}'] = '';
        $this->availableTemplateVars['{total_shipping}'] = '';
        $this->availableTemplateVars['{total_shipping_tax_excl}'] = '';
        $this->availableTemplateVars['{total_shipping_tax_incl}'] = '';
        $this->availableTemplateVars['{total_wrapping}'] = '';
        $this->availableTemplateVars['{total_tax_paid}'] = '';
        $this->availableTemplateVars['{payment}'] = '';

    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = cdkeysUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (cdkeysUpdate::version($this->version) < cdkeysUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = cdkeysUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (cdkeysUpdate::version($this->version) < cdkeysUpdate::version(cdkeysUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public static function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        if ($part == 1) {
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
    }

    function install()
    {
        if ($this->psversion() == 5 || $this->psversion() == 6 || $this->psversion() == 7) {
            if (!parent::install() or
                !Configuration::updateValue('update_' . $this->name, '0') or
                !$this->registerHook('displayadminOrder') or
                !$this->registerHook('orderDetailDisplayed') or
                !$this->registerHook('actionOrderStatusUpdate') or
                !$this->registerHook('actionAdminControllerSetMedia') or
                !$this->registerHook('displayCustomerAccount') or
                !$this->registerHook('displayBackOfficeHeader') or
                !$this->registerHook('header') or
                !$this->installdb() or !Configuration::updateValue('IV_ROW_DELIMITER', '\r') or
                !Configuration::updateValue('IV_COL_DELIMITER', ',') or !$this->createMenu()) {
                return false;
            }
        }
        return true;
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        // for update purposes
    }

    public function createMenu()
    {
        //parent menu
        $parent_tab = new Tab();
        $parent_tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $parent_tab->name[$lang['id_lang']] = 'CdKeys Manager';
        }
        $parent_tab->class_name = 'AdminCdKeys';
        $parent_tab->id_parent = 0;
        $parent_tab->module = $this->name;
        $parent_tab->add();

        //groups of keys
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Groups';
        }
        $tab->class_name = 'AdminCdKeysGroups';
        $tab->id_parent = $parent_tab->id;
        $tab->module = $this->name;
        $tab->add();

        //keys
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'CdKeys';
        }
        $tab->class_name = 'AdminCdKeysList';
        $tab->id_parent = $parent_tab->id;
        $tab->module = $this->name;
        $tab->add();

        //used keys
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Used CdKeys';
        }
        $tab->class_name = 'AdminCdKeysArchive';
        $tab->id_parent = $parent_tab->id;
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        // Tabs
        $idTabs = array();
        $idTabs[] = Tab::getIdFromClassName('AdminCdKeysGroups');
        $idTabs[] = Tab::getIdFromClassName('AdminCdKeysList');
        $idTabs[] = Tab::getIdFromClassName('AdminCdKeysArchive');
        $idTabs[] = Tab::getIdFromClassName('AdminCdKeys');
        foreach ($idTabs as $idTab) {
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }
        return true;
    }

    private function installdb()
    {
        $prefix = _DB_PREFIX_;
        $engine = _MYSQL_ENGINE_;
        $statements = array();
        $statements[] = "CREATE TABLE IF NOT EXISTS `${prefix}cdkey_group` (" . '`id_cdkey_group` int(10) NOT NULL AUTO_INCREMENT,' . '`id_product` int(10) NOT NULL, `id_shop` int(10) NOT NULL,' . '`name` VARCHAR(200),' . 'PRIMARY KEY (`id_cdkey_group`)' . ")";
        $statements[] = "CREATE TABLE IF NOT EXISTS `${prefix}cdkey` (" . '`id_cdkey` int(10) NOT NULL AUTO_INCREMENT,' . '`id_cdkey_group` int(10) NOT NULL, ' . '`code` VARCHAR(200),' . '`cdkeypwd` VARCHAR(255),' . '`active` int(1) NOT NULL DEFAULT 1,' . 'PRIMARY KEY (`id_cdkey`)' . ")";
        $statements[] = "CREATE TABLE IF NOT EXISTS `${prefix}cdkey_used` (" . '`id_cdkey_used` int(10) NOT NULL AUTO_INCREMENT,' . '`id_customer` int(10), `id_shop` int(10) NOT NULL,' . '`id_order` int(10),' . '`code` VARCHAR(200),' . '`cdkeypwd` VARCHAR(255),' . '`name` VARCHAR(200),' . '`product` VARCHAR(200),' . '`customer` VARCHAR(200),' . '`customer_mail` VARCHAR(200),' . 'PRIMARY KEY (`id_cdkey_used`)' . ")";
        $statements[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "cdkey_group_lang` (`id_cdkey_group` int(11) NOT NULL,`id_lang` int(11) NOT NULL, `title` VARCHAR(250)) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        foreach ($statements as $statement) {
            if (!Db::getInstance()->Execute($statement)) {
                return false;
            }
        }
        $this->inconsistency(0);
        return true;
    }

    private function maybeUpdateDatabase($table, $column, $type = "int(8)", $default = "1", $null = "NULL")
    {
        $sql = 'DESCRIBE ' . _DB_PREFIX_ . $table;
        $columns = Db::getInstance()->executeS($sql);
        $found = false;
        foreach ($columns as $col) {
            if ($col['Field'] == $column) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` ADD `' . $column . '` ' . $type . ' DEFAULT ' . $default . ' ' . $null)) {
                return false;
            }
        }
        return true;
    }

    public function inconsistency($return_report = 1)
    {
        $this->maybeUpdateDatabase('cdkey_used', 'id_cdkey_group', "int(9)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_used', 'id_order_detail', "int(9)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'av', "VARCHAR(6)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'us', "VARCHAR(6)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'hm', "INT(4)", 1, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'for_attr', "INT(6)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'id_product_attribute', "INT(6)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'syncstock', "INT(1)", 0, "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'template', "VARCHAR(250)", "'cdkey'", "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'invoice_name', "VARCHAR(250)", "'cdkey'", "NOT NULL");
        $this->maybeUpdateDatabase('cdkey_group', 'url', "TEXT", "''", "NULL");
        $this->maybeUpdateDatabase('cdkey_group_lang', 'group_desc', "TEXT", "''", "NULL");
        return;
    }

    public function renderFormMainSettings()
    {
        if (Tools::isSubmit('btnSubmit_ostates')) {
            $ostates = implode(',', Tools::getValue('cdkeys_ostates'));
            Configuration::updateValue("cdkeys_ostates", $ostates);
            $groups = implode(',', Tools::getValue('cdkeys_groups'));
            Configuration::updateValue("cdkeys_groups", $groups);
        }

        $fields_form = array(
            'form' => array(
                'input' => array(
                    array(
                        'type' => 'html',
                        'label' => $this->l('Select order states'),
                        'name' => 'cdkeys_ostates',
                        'html_content' => $this->renderFormOrderStates(),
                    ),
                    array(
                        'type' => 'html',
                        'label' => $this->l('Groups of customers permitted to receive cdkey'),
                        'name' => 'cdkeys_groups',
                        'html_content' => $this->renderFormGroups(),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            )
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = 'id_cdkeys_ostates';
        $helper->identifier = 'cdkeys_ostates';
        $helper->submit_action = 'btnSubmit_ostates';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }


    public function renderFormGroups()
    {
        $explode_states = explode(',', Configuration::get('cdkeys_groups'));
        $this->context->smarty->assign('cdkeys_groups', $explode_states);
        $this->context->smarty->assign('cdkeys_customerGroups', Group::getGroups($this->context->language->id, $this->context->shop->id));
        return $this->display(__file__, 'views/templates/admin/customerGroups.tpl');
    }

    public function renderFormOrderStates()
    {
        $explode_states = explode(',', Configuration::get('cdkeys_ostates'));
        $this->context->smarty->assign('cdkeys_ostates', $explode_states);
        $this->context->smarty->assign('orderStates', OrderState::getOrderStates($this->context->language->id));
        return $this->display(__file__, 'views/templates/admin/orderstates.tpl');
    }

    public function getContent()
    {
        $this->searchTool = new searchToolcdkeys('cdkeys');
        $this->emailTemplatesManager = new cdkeysEmailTemplatesManager($this->name, $this->availableTemplateVars);
        $output = "";
        if (Tools::isSubmit('selecttab')) {
            Configuration::updateValue('cdkey_lasttab', Tools::getValue('selecttab'));
        }

        if (Tools::isSubmit('CDKEYS_MAX_ORDERTOTAL')) {
            Configuration::updateValue('CDKEYS_MAX_ORDERTOTAL', Tools::getValue('CDKEYS_MAX_ORDERTOTAL'));
            Configuration::updateValue('CDKEY_HOWDELIVER', Tools::getValue('CDKEY_HOWDELIVER'));
            $multititle = array();
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $multititle[(int)$lang['id_lang']] = Tools::getValue('CDKEYS_MULTITITLE_' . $lang['id_lang']);
            }
            Configuration::updateValue('CDKEYS_MULTITITLE', $multititle, true);
        }

        if (Tools::isSubmit('SaveCdkeySettings')) {
            Configuration::updateValue('cdkey_group', Tools::getValue('cdkey_group'));
            Configuration::updateValue('cdkey_active', Tools::getValue('cdkey_active'));
            Configuration::updateValue('IV_ROW_DELIMITER', Tools::getValue('IV_ROW_DELIMITER'));
            Configuration::updateValue('IV_COL_DELIMITER', Tools::getValue('IV_COL_DELIMITER'));
        }

        if (Tools::isSubmit('SaveSettingsCdkey')) {
            Configuration::updateValue('cdkey_prevdup', Tools::getValue('cdkey_prevdup'));
            Configuration::updateValue('cdkey_inc_inv', Tools::getValue('cdkey_inc_inv'));
            Configuration::updateValue('cdkey_pagination', Tools::getValue('cdkey_pagination'));
            Configuration::updateValue('cdkey_pagination_nb', Tools::getValue('cdkey_pagination_nb'));
            Configuration::updateValue('cdkey_pack', Tools::getValue('cdkey_pack'));
        }

        if (Tools::isSubmit('upload_csv')) {
            $plik_tmp = $_FILES['upload_csv']['tmp_name'];
            $plik_nazwa = $_FILES['upload_csv']['name'];
            $plik_rozmiar = $_FILES['upload_csv']['size'];
            if (is_uploaded_file($plik_tmp)) {
                $date = date("Y-m-d-h-i-s");
                if (move_uploaded_file($plik_tmp, '..' . $this->dir . "$date.csv")) {
                }
            }
            $output .= "<div class=\"bootstrap\" style=\"margin-top:20px;\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" . $this->l('CSV file uploaded') . "</div></div>";
        }

        if (Tools::isSubmit('delete_csv_file')) {
            if (file_exists(".." . $this->dir . Tools::getValue('fcsv'))) {
                unlink(".." . $this->dir . Tools::getValue('fcsv'));
            }
            $output .= "<div class=\"bootstrap\" style=\"margin-top:20px;\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" . $this->l('CSV file deleted') . "</div></div>";
        }

        if (Tools::isSubmit('submit_vouchers')) {
            $array_values = array();
            $file = Tools::file_get_contents(".." . $this->dir . Tools::getValue('filename'));
            if (Configuration::get('IV_ROW_DELIMITER') == '\n' || Configuration::get('IV_ROW_DELIMITER') == 'n') {
                $exp = explode("\n", $file);
            }
            if (Configuration::get('IV_ROW_DELIMITER') == '\r' || Configuration::get('IV_ROW_DELIMITER') == 'r') {
                $exp = explode("\r", $file);
            }
            if (Configuration::get('IV_ROW_DELIMITER') == '\r\n' || Configuration::get('IV_ROW_DELIMITER') == 'rn') {
                $exp = explode("\r\n", $file);
            }
            if (Configuration::get('IV_ROW_DELIMITER') == '\n\r' || Configuration::get('IV_ROW_DELIMITER') == 'nr') {
                $exp = explode("\r\n", $file);
            }


            $columns = "";
            foreach ($exp as $key => $value) {
                $first = 1;
                $exprow = explode(Configuration::get('IV_COL_DELIMITER'), "$exp[$key]");
                foreach ($exprow as $id => $val) {
                    ${"col" . $id} = Tools::getValue("col" . $id);
                    if (!(${"col" . $id} == "skip")) {
                        $columns .= ${"col" . $id} . ",";
                    }
                }
                if ($first == 1) {
                    break;
                }
            }
            $columns = Tools::substr($columns, 0, -1);
            foreach ($exp as $key => $value) {
                if (Tools::getIsset("add" . $key)) {
                    $exprow = explode(Configuration::get('IV_COL_DELIMITER'), "$exp[$key]");
                    $values = "";
                    foreach ($exprow as $id => $val) {
                        ${"col" . $id} = Tools::getValue("col" . "$id");
                        if (!(${"col" . $id} == "skip")) {
                            $values .= "'$val',";
                            $array_values[${"col" . "$id"}] = $val;
                        }
                    }
                    $values = Tools::substr($values, 0, -1);
                    $this->insert_cdkey($array_values);
                }
            }
            $output .= "<div class=\"bootstrap\" style=\"margin-top:20px;\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" . $this->l('CdKeys added to database') . "</div></div>";
        }

        return $output . $this->displayForm();
    }

    public function insert_cdkey($array_values)
    {
        $cdkey = new CdKeysList();
        $cdkey->id_cdkey_group;
        $cdkey->code = $array_values['code'];
        //add
        $cdkey->cdkeypwd = $array_values['cdkeypwd'];
        $cdkey->active = Configuration::get('cdkey_active');
        $cdkey->id_cdkey_group = Configuration::get('cdkey_group');
        $cdkey->add();
    }

    public function prepareorderstates($varname)
    {
        $ostates = OrderState::getOrderStates((int)$this->context->language->id);
        foreach (explode(",", Configuration::get($varname)) as $k => $v) {
            $array[] = $v;
        }
        $rstates = '';
        foreach ($ostates as $k => $v) {
            $rstates .= "<tr><td><label>" . $v['name'] . "</label></td><td><input type=\"checkbox\" name=\"" . $varname . "[]\" value=" . $v['id_order_state'] . " " . (in_array($v['id_order_state'], $array) ? 'checked' : '') . "></td></tr>";
        }
        return $rstates;
    }

    public function preparegroups($varname)
    {
        $groups = Group::getGroups((int)$this->context->language->id);
        foreach (explode(",", Configuration::get($varname)) as $k => $v) {
            $array[] = $v;
        }
        $rgroups = '';
        foreach ($groups as $k => $v) {
            $rgroups .= "<tr><td><label>" . $v['name'] . "</label></td><td><input type=\"checkbox\" name=\"" . $varname . "[]\" value=" . $v['id_group'] . " " . (in_array($v['id_group'], $array) ? 'checked' : '') . "></td></tr>";
        }
        return $rgroups;
    }

    public function runStatement($statement)
    {
        if (@!Db::getInstance()->Execute($statement)) {
            return false;
        }
        return true;
    }

    public function generateselect($colid)
    {
        $form = '
        <SELECT name="col' . $colid . '" style="font-size:12px; max-width:100px;">
        <option value="skip">skip column</option>
        <option value="code">CD Key</option>
        </SELECT>';
        return $form;
    }

    public function displayForm()
    {
        $form = '';
        $selected1 = "";
        $selected2 = "";
        $selected3 = "";
        $selected4 = "";
        $selected5 = "";
        $selected6 = "";

        if (Configuration::get('cdkey_lasttab') == 1) {
            $selected1 = "active";
        }
        if (Configuration::get('cdkey_lasttab') == 2) {
            $selected2 = "active";
        }
        if (Configuration::get('cdkey_lasttab') == 3) {
            $selected3 = "active";
        }
        if (Configuration::get('cdkey_lasttab') == 4) {
            $selected4 = "active";
        }
        if (Configuration::get('cdkey_lasttab') == 5) {
            $selected5 = "active";
        }
        if (Configuration::get('cdkey_lasttab') == 6) {
            $selected6 = "active";
        }

        if (Configuration::get('cdkey_lasttab') == 6) {
            $form .= $this->SettingsFormConditions();
        }
        if (Configuration::get('cdkey_lasttab') == 4) {
            $form .= $this->SettingsFormCdkeys();
        }

        if (Configuration::get('cdkey_lasttab') == 3) {
            $output = '';
            $form = '';
            if (Tools::isSubmit('importcsv')) {
                $file = Tools::file_get_contents(".." . $this->dir . Tools::getValue('importfile'));
                $exp = null;
                if (Configuration::get('IV_ROW_DELIMITER') == '\n' || Configuration::get('IV_ROW_DELIMITER') == 'n') {
                    $exp = explode("\n", $file);
                }
                if (Configuration::get('IV_ROW_DELIMITER') == '\r' || Configuration::get('IV_ROW_DELIMITER') == 'r') {
                    $exp = explode("\r", $file);
                }
                if (Configuration::get('IV_ROW_DELIMITER') == '\r\n' || Configuration::get('IV_ROW_DELIMITER') == 'rn') {
                    $exp = explode("\r\n", $file);
                }
                if (Configuration::get('IV_ROW_DELIMITER') == '\n\r' || Configuration::get('IV_ROW_DELIMITER') == 'nr') {
                    $exp = explode("\r\n", $file);
                }


                $rows = '<table border="1" class="table" style="width:100%; margin-bottom:15px; border: 1px solid #c0c0c0;"><form action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="filename" value="' . Tools::getValue('importfile') . '"/>';

                if (count($exp) > 0) {
                    foreach ($exp as $key => $value) {
                        $first = "1";
                        $exprow = explode(Configuration::get('IV_COL_DELIMITER'), "$exp[$key]");
                        $rows .= "<tr><td>add</td>";
                        foreach ($exprow as $id => $val) {
                            $rows .= "<td>" . $this->generateselect($id) . "</td>";
                        }
                        $rows .= "</tr>";
                        if ($first == 1) {
                            break;
                        }
                    }
                }

                if (count($exp) > 0) {
                    foreach ($exp as $key => $value) {
                        if (Tools::strlen($value) > 1) {
                            $exprow = explode(Configuration::get('IV_COL_DELIMITER'), "$exp[$key]");
                            $rows .= "<tr>";
                            $rows .= "<td><input type=\"checkbox\" checked=\"yes\" value=\"1\" name=\"add$key\"></td>";
                            foreach ($exprow as $id => $val) {
                                $rows .= "<td>$val</td>";
                            }
                            $rows .= "</tr>";
                        }
                    }
                }
                $rows .= "</table><input type='submit' name='submit_vouchers' value='" . $this->l('Add CD Keys to repository') . "' class='button'/></form>";
                $output .= "<div class=\"bootstrap\" style=\"margin-top:20px;\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" . $this->l('Loaded to import') . "</div></div>";

                $output .= '<div style="margin-bottom:120px;">
                    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                	<fieldset style="">
                    <legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('import file: ') . Tools::getValue('importfile') . '</legend>
                    ' . $rows . '
                    </fieldset>
                    </form>
                </div>';

                $form .= $output;
            }
            $this->bootstrap = false;
            $csvfiles = $this->getCsvFiles();
            $form .= '
            <div class="clearfix" style="clear:both; display:block; overflow:hidden;">
            <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
                <fieldset style="display:inline-block; width:45%; float:left; ">
                    <legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Upload CSV File') . '</legend>
                    <h3 style="margin-bottom:0px; padding-bottom:0px;">' . $this->l('Upload form') . '</h3>
                    <hr style="margin-top:5px;">
                        <input type="file" name="upload_csv" style="margin-left:100px;">
                    <center><input style="margin-top:10px;" type="submit" name="upload_csv" value="' . $this->l('Upload File') . '" class="button" /></center>
                </fieldset>
            </form>
            <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <fieldset style="display:inline-block; width:45%; float:right;">
    		<legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Uploaded CSV files') . '</legend>
            ' . $csvfiles . '
            </fieldset>
            </form>
            </div>';
        }

        if (Configuration::get('cdkey_lasttab') == 5) {
            $form .= $this->checkforupdates(0, 1);
        }

        if (Configuration::get('cdkey_lasttab') == 2) {

            $form .= $this->SettingsForm();
        }
        if (Configuration::get('cdkey_lasttab') == 1) {
            $form = '';
            $form .= $this->renderFormMainSettings();
        }

        return '
        <form name="selectform6" id="selectform6" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="6"></form>
        <form name="selectform5" id="selectform5" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="5"></form>
        <form name="selectform4" id="selectform4" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="4"></form>
        <form name="selectform3" id="selectform3" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="3"></form>
        <form name="selectform2" id="selectform2" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="2"></form>
        <form name="selectform1" id="selectform1" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="1"></form>' . "<div id='cssmenu'>
            <ul>
               <li class='bgver'><a><span>v" . $this->version . "</span></a></li>
               <li class='$selected1'><a href='#' onclick=\"selectform1.submit()\"><span>" . $this->l('Main settings') . "</span></a></li>
               <li class='$selected4'><a href='#' onclick=\"selectform4.submit()\"><span>" . $this->l('CdKeys settings') . "</span></a></li>               
               <li class='$selected6'><a href='#' onclick=\"selectform6.submit()\"><span>" . $this->l('Delivery conditions') . "</span></a></li>               
               <li class='$selected2'><a href='#' onclick=\"selectform2.submit()\"><span>" . $this->l('Import cdkeys settings') . "</span></a></li>
               <li class='$selected3'><a href='#' onclick=\"selectform3.submit()\"><span>" . $this->l('Import cdkeys') . "</span></a></li>
               <li class='$selected5'><a href='#' onclick=\"selectform5.submit()\"><span>" . $this->l('Updates') . "</span></a></li>               
            </ul>
        </div>" . '<link href="../modules/' . $this->name . '/views/css/css.css" rel="stylesheet" type="text/css" />' . $form . '
        <!-- Start of MyPresta Zendesk Widget script --><script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src=\'javascript:var d=document.open();d.domain="\'+n+\'";void(0);\',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write(\'<body onload="document._l();">\'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","prestasupport.zendesk.com");/*]]>*/</script><!-- End of MyPresta Zendesk Widget script -->';
    }

    public function getCsvFiles()
    {
        $dir = opendir('..' . $this->dir);
        $count = 0;
        while (false !== ($file = readdir($dir))) {
            if (($file == ".") || ($file == "..")) {
            } else {
                if (preg_match('@(.*)\.(csv)@i', $file)) {
                    $filesarray[$count]['name'] = $file;
                    $count++;
                }
            }
        }

        $csvfiles = "";
        if (isset($filesarray)) {
            if (count($filesarray) > 0) {
                foreach ($filesarray as $key => $value) {
                    $csvfiles = $csvfiles . '<div style="text-align:center; display:inline-block; padding:5px 10px; background:#FFF; border:1px solid #c0c0c0; margin-right:10px;"><a href="' . _PS_BASE_URL_ . _MODULE_DIR_ . 'cdkeys/' . $value['name'] . '" target="_blank" style="margin-bottom:10px; display:block; "><strong>' . $value['name'] . '</strong></a><form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data"><input type="hidden" name="importfile" value="' . $value['name'] . '"><input type="submit" value="' . $this->l('Import to database') . '" name="importcsv" class="button"/></form><form action="' . $_SERVER['REQUEST_URI'] . '" style="margin-top:10px" method="post" enctype="multipart/form-data"><input type="hidden" name="fcsv" value="' . $value['name'] . '"><input type="submit" value="' . $this->l('Delete') . '" name="delete_csv_file" class="button"/></form></div>';
                }
            } else {
                $csvfiles = $this->l('No Files');
            }
        } else {
            $csvfiles = $this->l('No Files');
        }
        return $csvfiles;
    }

    public function SettingsFormConditions()
    {
        $currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'), $this->context->language->id);

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Delivery conditions'),
                    'icon' => 'icon-plus-square'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Maximum order value'),
                        'name' => 'CDKEYS_MAX_ORDERTOTAL',
                        'prefix' => $currency->sign,
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->l('Automatically send cdkeys only if the order total is under defined value. If you do not want to use this condition just leave field empty or set its value as 0')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Send many codes in one mail'),
                        'name' => 'CDKEY_HOWDELIVER',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('By default, when someone will purchase several cdkeys - module sends each cdkey in separated email. If you will turn this option on - module will deliver all cdkeys in one email'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Title of email'),
                        'name' => 'CDKEYS_MULTITITLE',
                        'required' => true,
                        'lang' => true,
                        'desc' => $this->l('When option to send all cdkeys in one email will be active - you can define here the title of email that module will use to deliver cdkeys')
                    ),

                ),
                'submit' => array('title' => $this->l('Save'),)
            ),
        );
        $helper = new HelperForm();
        $helper->submit_action = 'SaveSettingsFormConditions';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->language->id;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($fields_form));
    }

    public function SettingsForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings of the codes'),
                    'icon' => 'icon-plus-square'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Group of keys'),
                        'name' => 'cdkey_group',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->l('Select the group of the codes, module will import codes to this group'),
                        'options' => array(
                            'query' => Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'cdkey_group` WHERE 1' . (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() ? Shop::addSqlRestriction(false) : '')),
                            'id' => 'id_cdkey_group',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('CSV file row delimiter'),
                        'name' => 'IV_ROW_DELIMITER',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->l('Specify the settings of the CSV file, usually') . ' ' . '\\n ' . $this->l('or') . ' \\r ' . $this->l('Depends on OS that you use')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('CSV file column delimiter'),
                        'name' => 'IV_COL_DELIMITER',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->l('Specify the settings of the CSV file, usually ; or ,')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active'),
                        'name' => 'cdkey_active',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('Define status of the imported cdkeys'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array('title' => $this->l('Save'),)
            ),
        );
        $helper = new HelperForm();
        $helper->submit_action = 'SaveCdkeySettings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );
        return $helper->generateForm(array($fields_form));
    }

    public function SettingsFormCdkeys()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Cdkeys settings'),
                    'icon' => 'icon-plus-square'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Duplicated keys'),
                        'name' => 'cdkey_prevdup',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('Do not add duplicated keys to database while you add new keys'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Include keys to invoice'),
                        'name' => 'cdkey_inc_inv',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('Option when active will include purchased cdkeys to invoice (near purchased products)'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Turn on pagination'),
                        'name' => 'cdkey_pagination',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('You can activate pagination in customer "my account" section with list of purchased cdkeys'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of keys to show in pagination'),
                        'name' => 'cdkey_pagination_nb',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                        'desc' => $this->l('Set the number of cdkeys that will appear on one page (pagination feature)')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Product packs support'),
                        'name' => 'cdkey_pack',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('PrestaShop allows to create a packs of product. This option - when active - will check if customer placed an order for "pack of products" and if so - if purchased product pack contains item that is associated with group of cdkeys. And if pack will contain such item - module will deliver cdkey for this customer'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array('title' => $this->l('Save'),)
            ),
        );
        $helper = new HelperForm();
        $helper->submit_action = 'SaveSettingsCdkey';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $row = Tools::getValue('IV_ROW_DELIMITER', Configuration::get('IV_ROW_DELIMITER'));
        $col = Tools::getValue('IV_COL_DELIMITER', Configuration::get('IV_COL_DELIMITER'));

        if ($row == "r" || $row == "n") {
            $row = '\\' . $row;
        }

        $multititle = array();
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $multititle[$lang['id_lang']] = Tools::getValue(
                'CDKEYS_MULTITITLE_' . $lang['id_lang'],
                Configuration::get('CDKEYS_MULTITITLE', $lang['id_lang']
                )
            );
        }

        return array(
            'cdkey_pagination_nb' => Tools::getValue('cdkey_pagination_nb', Configuration::get('cdkey_pagination_nb')),
            'cdkey_pagination' => Tools::getValue('cdkey_pagination', Configuration::get('cdkey_pagination')),
            'CDKEYS_MAX_ORDERTOTAL' => Tools::getValue('CDKEYS_MAX_ORDERTOTAL', Configuration::get('CDKEYS_MAX_ORDERTOTAL')),
            'cdkey_active' => Tools::getValue('cdkey_active', Configuration::get('cdkey_active')),
            'cdkey_group' => Tools::getValue('cdkey_group', Configuration::get('cdkey_group')),
            'cdkey_prevdup' => Tools::getValue('cdkey_prevdup', Configuration::get('cdkey_prevdup')),
            'cdkey_inc_inv' => Tools::getValue('cdkey_inc_inv', Configuration::get('cdkey_inc_inv')),
            'IV_ROW_DELIMITER' => $row,
            'IV_COL_DELIMITER' => $col,
            'cdkeys_ostates' => Configuration::get('cdkeys_ostates'),
            'cdkeys_groups' => Configuration::get('cdkeys_groups'),
            'cdkey_pack' => Tools::getValue('cdkey_pack', Configuration::get('cdkey_pack')),
            'CDKEY_HOWDELIVER' => Tools::getValue('CDKEY_HOWDELIVER', Configuration::get('CDKEY_HOWDELIVER')),
            'CDKEYS_MULTITITLE' => $multititle,
        );
    }

    public function hookdisplayHeader()
    {
        $this->context->controller->addJs($this->_path . 'views/js/cdkeys-pagination.js');
        if (Configuration::get('cdkey_pagination') == 1) {
            Media::addJsDef(array('cdkey_pagination_nb' => (int)Configuration::get('cdkey_pagination_nb')));
            Media::addJsDef(array('cdkey_pagination' => (int)Configuration::get('cdkey_pagination')));
        }
    }

    public function hookdisplayadminOrder($params)
    {
        $this->context->smarty->assign('cdkeys', CdKeysArchive::getAllByIdOrder($params['id_order']));
        return $this->display(__file__, 'adminOrder.tpl');
    }

    public function hookorderDetailDisplayed($params)
    {
        $this->context->smarty->assign('cdkeys', CdKeysArchive::getAllByIdOrder($params['order']->id));
        return $this->display(__file__, 'orderDetailDisplayed.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS(($this->_path) . 'views/css/cdkeys-admin.css', 'all');
    }

    public function hookcustomerAccount($params)
    {
        if ($this->context->customer->isLogged() == 1) {
            if ($this->psversion() == 6 || $this->psversion() == 5) {
                return $this->display(__file__, 'my-account-16.tpl');
            } elseif ($this->psversion() == 7) {
                return $this->display(__file__, 'my-account-17.tpl');
            }
        }
    }

    public function hookupdateOrderStatus($params)
    {
        return $this->hookactionOrderStatusUpdate($params);
    }

    public function getAvKeys($product, $row)
    {
        $return = Db::getInstance()->getRow("SELECT count(*) AS counter FROM `" . _DB_PREFIX_ . 'cdkey`' . " WHERE active = 1 AND id_cdkey_group=" . $row);
        if (isset($return['counter'])) {
            return $return['counter'];
        } else {
            return 0;
        }
    }

    public function hookactionOrderStatusUpdate($params)
    {
        $delivered = 0;
        $codes = array();
        $allowed = 0;
        $array = array();
        $array = explode(",", Configuration::get('cdkeys_groups'));
        $order = new Order($params['id_order']);


        if ((float)Configuration::get('CDKEYS_MAX_ORDERTOTAL') > 0) {
            $currency_order = new Currency($order->id_currency);
            $currency_default = new Currency(Configuration::get('PS_DEFAULT_CURRENCY'));
            $total_paid_default_currency = Tools::convertPriceFull($order->total_paid, $currency_order, $currency_default);
            if ($total_paid_default_currency > (float)Configuration::get('CDKEYS_MAX_ORDERTOTAL')) {
                return;
            }
        }


        $customer_groups = Customer::getGroupsStatic($order->id_customer);
        foreach ($customer_groups as $group) {
            if (in_array($group, $array)) {
                $allowed = 1;
            }
        }

        if ($allowed != 0) {
            $jest = 0;
            foreach (explode(",", Configuration::get('cdkeys_ostates')) as $k => $v) {
                if ($v == $params['newOrderStatus']->id) {
                    $jest = 1;
                }
            }

            if ($jest == 1) {
                $order = new Order($params['id_order']);
                $customer = new Customer($order->id_customer);
                $cdkeys_product_is_in_order = 0;
                $order_products = $order->getProducts();
                $order_products_with_pack_items = array();
                foreach ($order_products AS $number => $product) {
                    $product['id_product'] = $product['product_id'];
                    $order_products_with_pack_items[] = $product;
                    if (Pack::isPack($product['product_id']) && Configuration::get('cdkey_pack')) {
                        foreach (Pack::getItemTable($product['product_id'], $order->id_lang, true) AS $pack_product) {
                            $pack_product['product_id'] = $pack_product['id_product'];
                            $pack_product['product_quantity'] = $product['product_quantity'];
                            $pack_product['product_name'] = $pack_product['name'];
                            $pack_product['product_attribute_id'] = $pack_product['id_product_attribute_item'];
                            $pack_product['id_order_detail'] = $product['id_order_detail'];
                            $order_products_with_pack_items[] = $pack_product;
                        }
                    }
                }


                foreach (CdKeysGroup::getAll() as $key => $value) {
                    foreach ($order_products_with_pack_items as $number => $product) {
                        $purchased_product = new Product($product['product_id'], true, $order->id_lang);
                        if ($value['for_attr'] == 1) {
                            if ($value['id_product'] == $product['product_id'] && $value['id_product_attribute'] == $product['product_attribute_id']) {
                                for ($i = 1; $i <= $product['product_quantity']; $i++) {
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_order_detail'] = (isset($product['id_order_detail']) ? $product['id_order_detail'] : 0);
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_cdkey_group'] = $value['id_cdkey_group'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['syncstock'] = $value['syncstock'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_product'] = $product['product_id'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_product_attribute'] = $product['product_attribute_id'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_name'] = $product['product_name'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_description'] = (isset($purchased_product->description) ? $purchased_product->description : '-- ' . $this->l('No product description') . ' --');
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_description_short'] = (isset($purchased_product->description_short) ? $purchased_product->description_short : '-- ' . $this->l('No product short description') . ' --');
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['group_name'] = $value['name'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['group_url'] = $value['url'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['invoice_name'] = $value['invoice_name'];
                                }
                                $cdkeys_product_is_in_order = 1;
                            }
                        } else {
                            if ($value['id_product'] == $product['product_id']) {
                                for ($i = 1; $i <= $product['product_quantity']; $i++) {
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_order_detail'] = (isset($product['id_order_detail']) ? $product['id_order_detail'] : 0);
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_cdkey_group'] = $value['id_cdkey_group'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['syncstock'] = $value['syncstock'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_product'] = $product['product_id'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['id_product_attribute'] = $product['product_attribute_id'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_name'] = $product['product_name'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_description'] = (isset($purchased_product->description) ? $purchased_product->description : '-- ' . $this->l('No product description') . ' --');
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['product_description_short'] = (isset($purchased_product->description_short) ? $purchased_product->description_short : '-- ' . $this->l('No product short description') . ' --');
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['group_name'] = $value['name'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['group_url'] = $value['url'];
                                    $list_of_codes[$product['id_product'] . "_" . $i . '_' . $product['product_attribute_id']]['invoice_name'] = $value['invoice_name'];
                                }
                                $cdkeys_product_is_in_order = 1;
                            }
                        }
                    }
                }

                $cdkey_already_sent = CdKeysArchive::verifyIfExists($order->id_customer, $params['id_order']);
                $multikey = array();


                if ($cdkey_already_sent == false && $jest == 1 && $cdkeys_product_is_in_order == 1) {
                    $keysForInvoice = array();
                    $NamesForInvoice = array();
                    foreach ($list_of_codes as $k => $v) {
                        $id_lang = Context::getContext()->language->id;
                        $id_shop = Context::getContext()->shop->id;
                        $CdKeysGroup = new CdKeysGroup($v['id_cdkey_group'], $order->id_lang, $this->context->shop->id);
                        $templateVars['{firstname}'] = $customer->firstname;
                        $templateVars['{lastname}'] = $customer->lastname;
                        $templateVars['{product}'] = $v['product_name'];
                        $templateVars['{product_description}'] = $v['product_description'];
                        $templateVars['{product_description_short}'] = $v['product_description_short'];
                        $templateVars['{group_url}'] = $v['group_url'];
                        $templateVars['{group_desc}'] = $v['group_desc'];
                        $templateVars['{group_title}'] = $v['title'];

                        if ($CdKeysGroup->hm > 0) {
                            $keysForSqlQuery = array();
                            for ($i = 0; $i < $CdKeysGroup->hm; $i++) {
                                $code = CdKeysList::getOne($v['id_cdkey_group']);
                                if ($code) {
                                    $keysForSqlQuery[] = $code['code'];
                                    $CdKeysArchive = new CdKeysArchive();
                                    $CdKeysArchive->id_order = $params['id_order'];
                                    $CdKeysArchive->id_order_detail = $v['id_order_detail'];
                                    $CdKeysArchive->code = $code['code'];
                                    //add
                                    $CdKeysArchive->cdkeypwd = $code['cdkeypwd'];
                                    $CdKeysArchive->name = $v['group_name'];
                                    $CdKeysArchive->id_cdkey_group = $v['id_cdkey_group'];
                                    $CdKeysArchive->product = $v['product_name'];
                                    $CdKeysArchive->customer = $customer->firstname . " " . $customer->lastname;
                                    $CdKeysArchive->customer_mail = $customer->email;
                                    $CdKeysArchive->id_customer = $customer->id;
                                    $CdKeysArchive->id_shop = $this->context->shop->id;
                                    if ($CdKeysArchive->add()) {
                                        $CdKeysList = new CdKeysList($code['id_cdkey']);
                                        $CdKeysList->delete();
                                    }

                                    if ($v['syncstock'] == 1) {
                                        StockAvailable::setQuantity((int)$v['id_product'], (int)$v['id_product_attribute'], $this->getAvKeys(null, $CdKeysArchive->id_cdkey_group), (int)$CdKeysArchive->id_shop);
                                    }

                                    $keysForInvoice[$v['id_order_detail']][] = $code['code'];
                                } else {
                                    $keysForSqlQuery[] = $this->l('No CdKey Available, please contact with us');
                                    $keysForInvoice[$v['id_order_detail']][] = $this->l('No CdKey Available, please contact with us');
                                }
                            }
                            $templateVars['{code}'] = implode("<br/>", $keysForSqlQuery);
                            //add
                            $templateVars['{cdkeypwd}'] = $code['cdkeypwd'];

                        }

                        if (Configuration::get('CDKEY_HOWDELIVER') == 1) {
                            $multikey[$k]['firstname'] = $templateVars['{firstname}'];
                            $multikey[$k]['lastname'] = $templateVars['{lastname}'];
                            $multikey[$k]['code'] = $templateVars['{code}'];
                            //add
                            $multikey[$k]['cdkeypwd'] = $templateVars['{cdkeypwd}'];
                            $multikey[$k]['product'] = $templateVars['{product}'];
                            $multikey[$k]['product_description'] = $templateVars['{product_description}'];
                            $multikey[$k]['product_description_short'] = $templateVars['{product_description_short}'];
                            $multikey[$k]['group_url'] = $templateVars['{group_url}'];
                            $multikey[$k]['cdkeys_group_title'] = $CdKeysGroup->title;
                            $multikey[$k]['cdkeys_group_desc'] = $CdKeysGroup->group_desc;
                        }

                        //GLOBAL EMAIL VARIABLES
                        $templateVars['{id_order}'] = $params['id_order'];

                        // NEW
                        $templateVars['{email}'] = $customer->email;
                        if (isset($params['id_order'])) {
                            $order = new Order($params['id_order']);
                            $currency = new Currency($order->id_currency);
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
                        }

                        // REPLACE GROUP DESC
                        if (Configuration::get('CDKEY_HOWDELIVER') != 1) {
                            Mail::Send($order->id_lang, $CdKeysGroup->template, $this->replaceMailTitle($CdKeysGroup->title, $templateVars), $templateVars, strval($customer->email), null, strval(Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop)), strval(Configuration::get('PS_SHOP_NAME', null, null, $id_shop)), null, null, dirname(__file__) . '/mails/', false, $id_shop);
                        }

                        /**
                         * $url_address = "localhost/17210/";
                         * $to = "48698400799";
                         * $text = "Cdkey code you bought: ".$CdKeysArchive->code;
                         * $unicode=0;
                         * $type = "customer";
                         *
                         * $status = "";
                         * $query = "to=".urlencode($to)."&text=".urlencode($text)."&unicode=".$unicode."&type=".$type."&transaction=".$transaction;
                         *
                         * function URLopen($url)
                         * {
                         * $dh = fopen("$url",'r');
                         * $result = fread($dh, 8192);
                         * return $result;
                         * }
                         * $data = @URLopen("http://".$url_address."/modules/prestasms/api.php?".$query);
                         **/

                        $NamesForInvoice[$v['id_order_detail']] = $v['invoice_name'].': ';

                    }
                    $this->updateOrderDetail($NamesForInvoice, $keysForInvoice);

                    $email_title = Configuration::get('CDKEYS_MULTITITLE', $order->id_lang);
                    if (Configuration::get('CDKEY_HOWDELIVER') == 1) {
                        $this->context->smarty->assign('multikey', $multikey);
                        $html_to_send = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'cdkeys/views/templates/email/multikey-instance.tpl');
                        $templateVars['{firstname}'] = $customer->firstname;
                        $templateVars['{lastname}'] = $customer->lastname;
                        $templateVars['{html_to_send}'] = $html_to_send;
                        $templateVars['{group_desc}'] = $this->replaceMailTitle($v['group_desc'], $templateVars);

                        Mail::Send($order->id_lang, 'multikeymail', $this->replaceMailTitle($email_title, $templateVars), $templateVars, strval($customer->email), null, strval(Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop)), strval(Configuration::get('PS_SHOP_NAME', null, null, $id_shop)), null, null, dirname(__file__) . '/mails/', false, $id_shop);
                    }
                }
            }
        }
    }

    public function replaceMailTitle($title, $array)
    {
        foreach ($array AS $key => $value) {
            $title = str_replace($key, $value, $title);
        }
        return $title;
    }

    public function updateOrderDetail($namesForInvoice, $keysForInvoice)
    {
        foreach ($keysForInvoice AS $id_order_detail => $codes) {
            if (Configuration::get('cdkey_inc_inv') == true) {
                $order_detail = Db::getInstance()->getRow("SELECT * FROM `" . _DB_PREFIX_ . 'order_detail`' . " WHERE id_order_detail=" . $id_order_detail);
                $product_name = explode(' |#| ', $order_detail['product_name']);
                $product_name[1] = '';
                $product_name[1] .= $namesForInvoice[$id_order_detail]. ' ' . implode(", ", $codes);
                $new_product_name = implode(' |#| ', $product_name);
                $od = new OrderDetail($id_order_detail);
                $od->product_name = $new_product_name;
                $od->save();
            }
        }

        /**
         * if (isset($v['id_order_detail'])) {
         * if ($v['id_order_detail']) {
         * if (Configuration::get('cdkey_inc_inv') == true) {
         * $order_detail = Db::getInstance()->getRow("SELECT * FROM `" . _DB_PREFIX_ . 'order_detail`' . " WHERE id_order_detail=" . $v['id_order_detail']);
         * $product_name = explode(' |#| ', $order_detail['product_name']);
         * $product_name[1] = '';
         * foreach ($code AS $vv => $c) {
         * $product_name[1] .= ' ' . $v['invoice_name'] . ': ' . $c;
         * }
         * $new_product_name = implode(' |#| ', $product_name);
         * $od = new OrderDetail($v['id_order_detail']);
         * $od->product_name = $new_product_name;
         * $od->save();
         * }
         * }
         * }
         * **/
    }
}

class cdkeysUpdate extends cdkeys
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

if (file_exists(_PS_MODULE_DIR_ . 'cdkeys/lib/emailTemplatesManager/emailTemplatesManager.php')) {
    require_once _PS_MODULE_DIR_ . 'cdkeys/lib/emailTemplatesManager/emailTemplatesManager.php';
}
if (file_exists(_PS_MODULE_DIR_ . 'cdkeys/lib/searchTool/searchTool.php')) {
    require_once _PS_MODULE_DIR_ . 'cdkeys/lib/searchTool/searchTool.php';
}
?>