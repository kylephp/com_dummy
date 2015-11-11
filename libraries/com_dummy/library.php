<?php
/**
 * Dummy Library file.
 * Including this file into your application will make dummy available to use.
 *
 * @package    Dummy.Library
 * @copyright  Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

// Define dummy Library Folder Path
define('JPATH_DUMMY_LIBRARY', __DIR__);

// Bootstraps redCORE
RBootstrap::bootstrap();

// Register library prefix
RLoader::registerPrefix('Dummy', JPATH_DUMMY_LIBRARY);

// Make available the dummy fields
JFormHelper::addFieldPath(JPATH_DUMMY_LIBRARY . '/form/fields');

// Make available the dummy form rules
JFormHelper::addRulePath(JPATH_DUMMY_LIBRARY . '/form/rules');
