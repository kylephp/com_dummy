<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * The objects controller
 *
 * @package     Dummy.Backend
 * @subpackage  Controller.Objects
 * @since       1.0
 */
class DummyControllerObjects extends RControllerAdmin
{
	/**
	 * Method to get object list
	 * @return void
	 */
	public function ajaxGetObjectList()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel('objects');
		$model->setState('filter.published', 1);
		$model->setState('list.limit', 0);
		$objects = $model->getItems();
		$data = array();

		foreach ($objects as $object) 
		{
			$o = new stdClass;
			$o->id = $object->id;
			$o->name = $object->name;
			$params = new JRegistry($object->params);
			$o->lat = $params->get('lat');
			$o->lng = $params->get('lon');

			$data[] = $o;
		}

		echo json_encode($data);

		$app->close();
	}
}
