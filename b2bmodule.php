<?php
/**
* 2007-2024 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class B2bmodule extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'b2bmodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mitesh Nile';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('B2B Module');
        $this->description = $this->l('This module will add a B2B action button on the products');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->controllers = array('b2bform');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('B2BMODULE_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('Header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayProductAdditionalInfo');
    }


    public function uninstall()
    {
        Configuration::deleteByName('B2BMODULE_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
  /*
    public function getContent()
    {
        if (Tools::isSubmit('submitB2bmoduleModule')) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        // Output from the existing template (configure.tpl and renderForm)
        $output1 = $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/admin/configure.tpl');
        $output3 = $this->renderForm();

        // Output from the custom function
        $output2 = $this->renderB2bModuleTemplate();

        // Combine all outputs
        $output = $output1 . $output2 . $output3;
        return $output;
    }
*/
    public function getContent()
{
    if (Tools::isSubmit('submitB2bmoduleModule')) {
        $this->postProcess();
    }

    $this->context->smarty->assign('module_dir', $this->_path);

    // Output from the existing template (configure.tpl and renderForm)
    $output1 = $this->context->smarty->fetch($this->getLocalPath() . 'views/templates/admin/configure.tpl');
    $output3 = $this->renderForm();

    // Combine all outputs
    $output = $output1 . $output3;

    // Render the b2bmodule.tpl template and append to the existing output
    $output .= $this->renderB2bModuleTemplate();

    return $output;
}

    /**
     * Custom function to render the b2bmodule template
     */
    protected function renderB2bModuleTemplate()
    {
        // Determine the current page (default to 1 if not set)
        $page = (int)Tools::getValue('page', 1);

        // Fetch data with pagination
        $limit = 10; // Number of rows per page
        $offset = ($page - 1) * $limit;
        $data = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'b2bmodule` LIMIT '.$limit.' OFFSET '.$offset);

        // Calculate total pages for pagination links
        $totalRows = Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'b2bmodule`');
        $totalPages = ceil($totalRows / $limit);

        // Assign data to the template
        $this->context->smarty->assign(array(
            'b2bData' => $data,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'link' => $this->context->link,
        ));

        // Render the template
        return $this->context->smarty->fetch($this->getLocalPath().'views/templates/admin/b2bmodule.tpl');
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitB2bmoduleModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'B2BMODULE_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'B2BMODULE_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'B2BMODULE_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'B2BMODULE_LIVE_MODE' => Configuration::get('B2BMODULE_LIVE_MODE', true),
            'B2BMODULE_ACCOUNT_EMAIL' => Configuration::get('B2BMODULE_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'B2BMODULE_ACCOUNT_PASSWORD' => Configuration::get('B2BMODULE_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
    
    public function hookDisplayProductAdditionalInfo($params)
    {
        // Access product information using $params
        $product = $params['product'];
        $product_id = $product->id; // Get the product ID
    
        // Create the button HTML with product reference in the URL
        $button_html = '<a class="btn btn-primary" href="' . $this->context->link->getModuleLink('b2bmodule', 'b2bform', ['id_product' => $product_id]) . '">Bulk Order Inquiry</a>';
    
        // Return the button HTML to be displayed
        return $button_html;
    }
    
}
