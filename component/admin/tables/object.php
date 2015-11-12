<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Object table
 *
 * @package     Dummy.Backend
 * @subpackage  Table
 * @since       1.0
 */
class DummyTableObject extends RTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $_tableName = 'dummy_objects';

	/**
	 * The primary key of the table
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $_tableKey = 'id';

	/**
	 * Field name to publish/unpublish table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';
}
