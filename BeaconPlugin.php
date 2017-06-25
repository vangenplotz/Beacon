<?php
namespace Craft;

/**
 * Beacon by Vangen & Plotz
 *
 * @author      Vangen & Plotz <http://vangenplotz.no>
 * @package     Beacon
 * @since       Craft 2.6
 * @copyright   Copyright (c) 2017, Vangen & Plotz AS
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 * @link        https://github.com/vangenplotz/Beacon
 */

class BeaconPlugin extends BasePlugin
{

	/**
	 * @return String
	 */
	public function getName()
	{
		return Craft::t('Beacon');
	}

	/**
	 * @return String
	 */
	public function getVersion()
	{
		return '0.0.1';
	}

	/**
	 * @return String
	 */
	public function getSchemaVersion()
	{
		return '0.0.1';
	}

	/**
	 * @return String
	 */
	public function getDeveloper()
	{
		return 'Vangen & Plotz AS';
	}

	/**
	 * @return String
	 */
	public function getDeveloperUrl()
	{
		return 'http://vangenplotz.no';
	}

	protected function defineSettings()
	{
		return array(
			'monitorSiteUrl' => array(AttributeType::Url, 'required' => true, 'label'=> Craft::t('Monitor site URL')),
			'monitorSecret' => array(AttributeType::String, 'required' => true, 'label'=> Craft::t('Monitor secret')),
			'siteName' => array(AttributeType::String, 'required' => true, 'default' => craft()->getSiteName(), 'label'=> Craft::t('Site name')),
			'siteSecret' => array(AttributeType::String, 'required' => true, 'default' => bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)), 'label'=> Craft::t('Site secret')),
			'autoupdate' => array(AttributeType::Bool, 'label'=> Craft::t('Auto update this plugin'))
		);
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('beacon/settings', array(
			'settings' => $this->getSettings()
		));
	}

	/**
	 * @return String
	 */
	public function getDescription()
	{
		return Craft::t('Monitor and update the site externally.');
	}

	public function registerSiteRoutes() {
		return array(
			'beacon/check' => array('action' => 'beacon/check'),

		);
	}

	public function prepSettings($settings)
	{
		craft()->beacon->pingMonitor($settings);
		return $settings;
	}

	public function onAfterInstall()
	{
		if (!craft()->isConsole()) {
			craft()->request->redirect(UrlHelper::getCpUrl('settings/plugins/beacon'));
		}
	}

}
