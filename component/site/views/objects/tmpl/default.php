<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('rdropdown.init');
JHtml::_('rbootstrap.tooltip');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('rholder.image', '50x50');

$saveOrderLink = 'index.php?option=com_dummy&task=objects.saveOrderAjax&tmpl=component';
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$ordering = ($listOrder == 'i.ordering');
$saveOrder = ($listOrder == 'i.ordering' && strtolower($listDirn) == 'asc');
$search = $this->state->get('filter.search');

$user = JFactory::getUser();
$userId = $user->id;

if (($saveOrder) && ($this->canEditState))
{
	JHTML::_('rsortablelist.sortable', 'table-items', 'adminForm', strtolower($listDirn), $saveOrderLink, false, true);
}

?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (pressbutton == 'objects.delete')
		{
			var r = confirm('<?php echo JText::_("COM_DUMMY_OBJECT_DELETE_ITEMS")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_dummy&view=objects" class="admin" id="adminForm" method="post" name="adminForm">
	<?php
	echo RLayoutHelper::render(
		'searchtools.default',
		array(
			'view' => $this,
			'options' => array(
				'searchField' => 'search',
				'searchFieldSelector' => '#filter_search',
				'limitFieldSelector' => '#list_objects_limit',
				'activeOrder' => $listOrder,
				'activeDirection' => $listDirn
			)
		)
	);
	?>
	<hr />
	<?php if (empty($this->items)) : ?>
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<div class="pagination-centered">
			<h3><?php echo JText::_('COM_DUMMY_NOTHING_TO_DISPLAY'); ?></h3>
		</div>
	</div>
	<?php else : ?>
	<table class="table table-striped" id="table-items">
		<thead>
			<tr>
				<th width="10" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="10">
					<?php if (version_compare(JVERSION, '3.0', 'lt')) : ?>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					<?php else : ?>
						<?php echo JHTML::_('grid.checkall'); ?>
					<?php endif; ?>
				</th>
				<?php if (($search == '') && ($this->canEditState)) : ?>
				<th width="40">
					<?php echo JHTML::_('rsearchtools.sort', '<i class=\'icon-sort\'></i>', 'i.ordering', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<th class="title" width="auto">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_DUMMY_OBJECT_NAME', 'i.name', $listDirn, $listOrder); ?>
				</th>
				<th width="20" nowrap="nowrap">
					<?php echo JHTML::_('rsearchtools.sort', 'COM_DUMMY_ID', 'i.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php $n = count($this->items); ?>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $orderkey = array_search($item->id, $this->ordering[0]); ?>
			<tr>
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<?php if (($search == '') && ($this->canEditState)) : ?>
				<td class="order nowrap center">
					<span class="sortable-handler hasTooltip <?php echo ($saveOrder) ? '' : 'inactive'; ?>">
						<i class="icon-move"></i>
					</span>
					<input type="text" style="display:none" name="order[]" value="<?php echo $orderkey + 1;?>" class="text-area-order" />
				</td>
				<?php endif; ?>
				<td>
					<?php $itemTitle = JHTML::_('string.truncate', $item->name, 50, true, false); ?>
					<?php if (($item->checked_out) || (!$this->canEdit)) : ?>
						<?php echo $itemTitle; ?>
					<?php else : ?>
						<?php echo JHtml::_('link', 'index.php?option=com_dummy&task=object.edit&id=' . $item->id, $itemTitle); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $item->id;?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->pagination->getPaginationLinks(null, array('showLimitBox' => true)); ?>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
