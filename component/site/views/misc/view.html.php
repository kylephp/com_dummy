<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML Demo View class for the Dummy component
 *
 * @since  1.5
 */
class DummyViewMisc extends JViewLegacy
{
	public function display($tpl = null)
	{

		$this->policies = $this->getModel()->getPolicies();

		/*
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		*/

		return parent::display($tpl);
	}
}
