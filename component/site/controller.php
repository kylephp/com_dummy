<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dummy
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Dummy Component Controller
 *
 * @since  3.1
 */
class DummyController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean        $cachable   If true, the view output will be cached
	 * @param   mixed|boolean  $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   3.1
	 */
	public function display($cachable = true, $urlparams = false)
	{
		$user = JFactory::getUser();

		// Set the default view name and format from the Request.
		$vName = $this->input->get('view', 'demo');
		$this->input->set('view', $vName);
		
		$safeurlparams = array('id' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
								'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'lang' => 'CMD');

		return parent::display($cachable, $safeurlparams);
	}
}
