<?php
/**
 * @package     Dummy.Frontend
 * @subpackage  Dummy
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$input = JFactory::getApplication()->input;

JLoader::import('joomla.html.parameter');

$option = $input->getCmd('option');
$view   = $input->getCmd('view');

// Register component prefix
JLoader::registerPrefix('Dummy', __DIR__);

// Register library prefix
JLoader::registerPrefix('Dummy', JPATH_LIBRARIES . '/com_dummy');

// Loading helper
JLoader::import('joomla.html.pagination');
JLoader::import('dummy', JPATH_COMPONENT . '/helpers');
JLoader::import('route', JPATH_COMPONENT . '/helpers');

$controller = $input->getCmd('view');

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'demo';
	$input->set('view', 'demo');
}

require_once JPATH_COMPONENT . '/controllers/' . $controller . '.php';

// Execute the controller
$controller = JControllerLegacy::getInstance('dummy');
$controller->execute($input->getCmd('task'));
$controller->redirect();
