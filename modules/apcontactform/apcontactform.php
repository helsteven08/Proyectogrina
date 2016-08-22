<?php
/**
 * 2007-2015 PrestaShop
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
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
	exit;

class Apcontactform extends Module
{

	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'apcontactform';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Apollotheme';
		$this->need_instance = 0;

		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Apollo Contact Form');
		$this->description = $this->l('This module will show contact from in font-office');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	/**
	 * Don't forget to create update methods if needed:
	 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
	 */
	public function install()
	{
		Configuration::updateValue('APCONTACTFORM_LIVE_MODE', false);

		return parent::install() &&
						$this->registerHook('header') &&
						$this->registerHook('backOfficeHeader');
						// && $this->registerHook('displayFooter');
	}

	public function uninstall()
	{
		Configuration::deleteByName('APCONTACTFORM_LIVE_MODE');

		return parent::uninstall();
	}

	/**
	 * Load the configuration form
	 */
//	public function getContent()
//	{
//		/**
//		 * If values have been submitted in the form, process.
//		 */
//		if (((bool)Tools::isSubmit('submitApcontactformModule')) == true)
//			$this->postProcess();
//
//		$this->context->smarty->assign('module_dir', $this->_path);
//
//		$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
//
//		return $output.$this->renderForm();
//	}

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
		$helper->submit_action = 'submitApcontactformModule';
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
						'name' => 'APCONTACTFORM_LIVE_MODE',
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
						'name' => 'APCONTACTFORM_ACCOUNT_EMAIL',
						'label' => $this->l('Email'),
					),
					array(
						'type' => 'password',
						'name' => 'APCONTACTFORM_ACCOUNT_PASSWORD',
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
			'APCONTACTFORM_LIVE_MODE' => Configuration::get('APCONTACTFORM_LIVE_MODE', true),
			'APCONTACTFORM_ACCOUNT_EMAIL' => Configuration::get('APCONTACTFORM_ACCOUNT_EMAIL', 'contact@prestashop.com'),
			'APCONTACTFORM_ACCOUNT_PASSWORD' => Configuration::get('APCONTACTFORM_ACCOUNT_PASSWORD', null),
		);
	}

	/**
	 * Save form data.
	 */
	protected function postProcess()
	{
		$form_values = $this->getConfigFormValues();

		foreach (array_keys($form_values) as $key)
			Configuration::updateValue($key, Tools::getValue($key));
	}

	/**
	 * Add the CSS & JavaScript files you want to be loaded in the BO.
	 */
//	public function hookBackOfficeHeader()
//	{
//		if (Tools::getValue('module_name') == $this->name)
//		{
//			$this->context->controller->addJS($this->_path.'views/js/back.js');
//			$this->context->controller->addCSS($this->_path.'views/css/back.css');
//		}
//	}

	/**
	 * Add the CSS & JavaScript files you want to be added on the FO.
	 */
	public function hookHeader()
	{
		$page_name = $this->context->smarty->tpl_vars['page_name']->value;
		if ($page_name == 'contact')
			return;

		$this->context->controller->addJS($this->_path.'/views/js/front.js');
		$this->context->controller->addCSS(_THEME_CSS_DIR_.'contact-form.css');
		$this->context->controller->addJS(_THEME_JS_DIR_.'contact-form.js');
		$this->context->controller->addJS(_PS_JS_DIR_.'validate.js');

	}

	public function hookDisplayFooter()
	{
		$page_name = $this->context->smarty->tpl_vars['page_name']->value;
		if ($page_name == 'contact')
			return;

		if (!$this->isCached('apcontactform.tpl', $this->getCacheId()))
		{
			$this->assignOrderList();
			$this->errors = '';
			$email = Tools::safeOutput(Tools::getValue('from', ((isset($this->context->cookie) && isset($this->context->cookie->email) && Validate::isEmail($this->context->cookie->email)) ? $this->context->cookie->email : '')));
			$this->context->smarty->assign(array(
				'errors' => $this->errors,
				'email' => $email,
				'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD'),
				'max_upload_size' => (int)Tools::getMaxUploadSize()
			));

			if (($id_customer_thread = (int)Tools::getValue('id_customer_thread')) && $token = Tools::getValue('token'))
			{
				$customer_thread = Db::getInstance()->getRow('
				SELECT cm.*
				FROM '._DB_PREFIX_.'customer_thread cm
				WHERE cm.id_customer_thread = '.(int)$id_customer_thread.'
				AND cm.id_shop = '.(int)$this->context->shop->id.'
				AND token = \''.pSQL($token).'\'
			');

				$order = new Order((int)$customer_thread['id_order']);
				if (Validate::isLoadedObject($order))
					$customer_thread['reference'] = $order->getUniqReference();
				$this->context->smarty->assign('customerThread', $customer_thread);
			}

			$this->context->smarty->assign(array(
				'contacts' => Contact::getContacts($this->context->language->id),
				'message' => html_entity_decode(Tools::getValue('message'))
			));
		}
		return $this->display(__FILE__, 'apcontactform.tpl', $this->getCacheId());
	}

	protected function assignOrderList()
	{
		if ($this->context->customer->isLogged())
		{
			$this->context->smarty->assign('isLogged', 1);

			$products = array();
			$result = Db::getInstance()->executeS('
			SELECT id_order
			FROM '._DB_PREFIX_.'orders
			WHERE id_customer = '.(int)$this->context->customer->id.Shop::addSqlRestriction(Shop::SHARE_ORDER).' ORDER BY date_add');

			$orders = array();

			foreach ($result as $row)
			{
				$order = new Order($row['id_order']);
				$date = explode(' ', $order->date_add);
				$tmp = $order->getProducts();
				foreach ($tmp as $key => $val)
				{
					$products[$row['id_order']][$val['product_id']] = array('value' => $val['product_id'], 'label' => $val['product_name']);
					# validate module
					unset($key);
				}

				$orders[] = array('value' => $order->id, 'label' => $order->getUniqReference().' - '.Tools::displayDate($date[0], null), 'selected' => (int)$this->getOrder() == $order->id);
			}

			$this->context->smarty->assign('orderList', $orders);
			$this->context->smarty->assign('orderedProductList', $products);
		}
	}

	protected function getOrder()
	{
		$id_order = false;
		if (!is_numeric($reference = Tools::getValue('id_order')))
		{
			$reference = ltrim($reference, '#');
			$orders = Order::getByReference($reference);
			if ($orders)
				foreach ($orders as $order)
				{
					$id_order = $order->id;
					break;
				}
		}
		else
			$id_order = Tools::getValue('id_order');
		return (int)$id_order;
	}
    
//  Remove here because appagebuilder choose these hook then not show at frontend
//	public function hookDisplayHome()
//	{
//		/* Place your code here. */
//	}
//
//	public function hookDisplayLeftColumn()
//	{
//		/* Place your code here. */
//	}
//
//	public function hookDisplayRightColumn()
//	{
//		/* Place your code here. */
//	}
//
//	public function hookDisplayTop()
//	{
//		/* Place your code here. */
//	}
//
//	public function hookDisplayTopColumn()
//	{
//		/* Place your code here. */
//	}

}
