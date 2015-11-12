<?php
/**
 * @package     Dummy.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * objects List View
 *
 * @package     Dummy.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class DummyViewObjects extends DummyView
{
	/**
	 * @var  boolean
	 */
	protected $items;

	/**
	 * @var  object
	 */
	public $state;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * @var array
	 */
	public $activeFilters;

	/**
	 * @var array
	 */
	public $stoolsOptions = array();

	/**
	 * Display the template list
	 *
	 * @param   string  $tpl  The template file to use
	 *
	 * @return   string
	 *
	 * @since   0.9.1
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$this->items			= $this->get('Items');
		$this->state			= $this->get('State');
		$this->pagination		= $this->get('Pagination');
		$this->filterForm		= $this->get('Form');
		$this->activeFilters	= $this->get('ActiveFilters');
		$this->items			= $this->getModel()->getItems();
		JFactory::getLanguage()->load('', JPATH_ADMINISTRATOR);

		// Items ordering
		$this->ordering = array();

		if ($this->items)
		{
			foreach ($this->items as $item)
			{
				$this->ordering[0][] = $item->id;
			}
		}

		// Edit permission
		$this->canEdit = false;

		if ($user->authorise('core.edit', 'com_dummy'))
		{
			$this->canEdit = true;
		}

		// Edit state permission
		$this->canEditState = false;

		if ($user->authorise('core.edit.state', 'com_dummy'))
		{
			$this->canEditState = true;
		}

		parent::display($tpl);
	}

	/**
	 * Get the page title
	 *
	 * @return  string  The title to display
	 *
	 * @since   0.9.1
	 */
	public function getTitle()
	{
		return JText::_('COM_DUMMY_OBJECT_ITEMS');
	}

	/**
	 * Get the tool-bar to render.
	 *
	 * @todo	The commented lines are going to be implemented once we have setup ACL requirements for Dummy
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$user = JFactory::getUser();

		$firstGroup = new RToolbarButtonGroup;
		$secondGroup = new RToolbarButtonGroup;
		$thirdGroup = new RToolbarButtonGroup;

		if ($user->authorise('core.create', 'com_dummy'))
		{
			$new = RToolbarBuilder::createNewButton('object.add');
			$firstGroup->addButton($new);
		}

		if ($user->authorise('core.edit', 'com_dummy'))
		{
			$edit = RToolbarBuilder::createEditButton('object.edit');
			$secondGroup->addButton($edit);
		}

		if ($user->authorise('core.delete', 'com_dummy'))
		{
			$delete = RToolbarBuilder::createDeleteButton('objects.delete');
			$secondGroup->addButton($delete);
		}

		$toolbar = new RToolbar;
		$toolbar->addGroup($firstGroup)->addGroup($secondGroup)->addGroup($thirdGroup);

		return $toolbar;
	}
}
