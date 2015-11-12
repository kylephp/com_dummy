<?php
/**
 * @package     Dummy.Front
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Dummy ItemEdit Model
 *
 * @package     Dummy.Component
 * @subpackage  Models.Item
 * @since       1.0
 *
 */
class DummyModelObject extends RModelAdmin
{
	/**
	 * Get the associated JTable
	 *
	 * @param   string  $name    Table name
	 * @param   string  $prefix  Table prefix
	 * @param   array   $config  Configuration array
	 *
	 * @return  JTable
	 */
	public function getTable($name = 'Object', $prefix = '', $config = array())
	{
		$class = get_class($this);

		if (empty($prefix))
		{
			$prefix = strstr($class, 'Model', true) . 'Table';
		}

		return parent::getTable($name, $prefix, $config);
	}
}
