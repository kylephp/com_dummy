<?php
/**
 * @package    Dummy.Installer
 *
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Find redCORE installer to use it as base system
if (!class_exists('Com_RedcoreInstallerScript'))
{
	$searchPaths = array(
		// Install
		dirname(__FILE__) . '/redCORE',
		// Discover install
		JPATH_ADMINISTRATOR . '/components/com_redcore'
	);

	if ($redcoreInstaller = JPath::find($searchPaths, 'install.php'))
	{
		require_once $redcoreInstaller;
	}
}

// Register component prefix
JLoader::registerPrefix('Dummy', __DIR__);

// Load Dummy Library
JLoader::import('dummy.library');

/**
 * Script file of Dummy component
 *
 * @package  Dummy.Installer
 *
 * @since    2.0
 */
class Com_DummyInstallerScript extends Com_RedcoreInstallerScript
{
	/**
	 * Installed Dummy version.
	 *
	 * @var string
	 */
	private $currentVersion = '';

	/**
	 * Array for moving current templates from db to files.
	 * Used on update process to version 2.1.17.
	 *
	 * @var array
	 */
	private $tempTemplates = array();

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  boolean          True on success
	 */
	public function installOrUpdate($parent)
	{
		parent::installOrUpdate($parent);

		$this->com_install();

		return true;
	}

	/**
	 * Main Dummy installer Events
	 *
	 * @return  void
	 */
	private function com_install()
	{
		// Diplay the installation message
		$this->displayInstallMsg();
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		// Uninstall extensions
		$this->com_uninstall();

		parent::uninstall($parent);
	}

	/**
	 * Main Dummy uninstaller Events
	 *
	 * @return  void
	 */
	private function com_uninstall()
	{
	}

	/**
	 * Display install message
	 *
	 * @return void
	 */
	public function displayInstallMsg()
	{
	}
}
