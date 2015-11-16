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
 * Dummy Objects Model
 *
 * @package     Dummy.Component
 * @subpackage  Models.Objects
 * @since       1.0
 *
 */
class DummyModelObjects extends RModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_objects';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'objects_limit';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitstartField = 'auto';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  [description]
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$filterFields = array(
				'name', 'i.name',
				'ordering', 'i.ordering',
				'published', 'i.published',
				'access', 'i.access', 'access_level',
				'i.id'
			);

			$config['filter_fields'] = $filterFields;
		}

		parent::__construct($config);
	}

	/**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return JDatabaseQuery A JDatabaseQuery object
	 */
	public function getListQuery()
	{
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();
		$app    = JFactory::getApplication();
		$value = $app->getUserStateFromRequest('global.list.limit', $this->paginationPrefix . 'limit', $app->getCfg('list_limit'), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);

		$value = $app->getUserStateFromRequest($this->context . '.limitstart', $this->paginationPrefix . 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		$query = $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'i.*'
			)
		);
		$query->from($db->qn('#__dummy_objects', 'i'));

		// Filter: like / search	
		$search = $this->getState('filter.search', '');

		if ($search != '')
		{
			$like = $db->quote('%' . $db->escape($search, true) . '%');

			$where = array();

			// Add search on item's title
			$where[] = $db->qn('i.name') . ' LIKE ' . $like;

			// Add search on item's ID
			$where[] = $db->qn('i.id') . ' LIKE ' . $like;

			$query->where('((' . implode(') OR (', $where) . '))');
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where($db->qn('i.published') . ' = ' . (int) $published);
		}
		elseif (($published === '') || (!isset($published)))
		{
			$query->where('(i.published IN (0, 1))');
		}

		// Check access level
		$query->where($db->qn('i.access') . ' IN (' . implode(',', $groups) . ')');

		// Get the ordering modifiers
		$orderCol	= $this->state->get('list.ordering', 'i.name');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return	string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   [description]
	 * @param   string  $direction  [description]
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'i.name', $direction = 'ASC')
	{
		$app = JFactory::getApplication();

		$filterSearch = $this->getUserStateFromRequest($this->context . '.filter_search', 'filter_search');
		$this->setState('filter.search', $filterSearch);

		$value = $app->getUserStateFromRequest('global.list.limit', $this->paginationPrefix . 'limit', $app->getCfg('list_limit'), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);

		$value = $app->getUserStateFromRequest($this->context . '.limitstart', $this->paginationPrefix . 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		parent::populateState($ordering, $direction);
	}
}
