<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Entry point
 *
 * @copyright   Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$redcoreLoader = JPATH_LIBRARIES . '/redcore/bootstrap.php';

if (!file_exists($redcoreLoader) || !JPluginHelper::isEnabled('system', 'redcore'))
{
	throw new Exception(JText::_('COM_DUMMY_REDCORE_INIT_FAILED'), 404);
}

include_once $redcoreLoader;

// Bootstraps redCORE
RBootstrap::bootstrap();

$app   = JFactory::getApplication();
$user  = JFactory::getUser();
$input = $app->input;

// Access check.
if (!$user->authorise('core.manage', 'com_dummy'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register component prefix
JLoader::registerPrefix('Dummy', __DIR__);

// Load Dummy Library
JLoader::import('dummy.library');

$controller = $input->getCmd('view', 'cpanel');

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'dummy';
	$input->set('view', 'cpanel');
}

RHelperAsset::load('dummy.backend.min.css', 'com_dummy');

$controller = JControllerLegacy::getInstance('Dummy');
$controller->execute($input->getCmd('task', ''));
$controller->redirect();
