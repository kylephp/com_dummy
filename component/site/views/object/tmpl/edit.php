<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Item
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();
var_dump('123');
JHTML::_('behavior.formvalidation');
JHtml::_('rjquery.chosen', 'select');
JHtml::_('behavior.modal', 'a.modal-thumb');
RHelperAsset::load('dummy.min.css', 'com_dummy');
$input = JFactory::getApplication()->input;
$template = $input->getString('tmpl');
?>

<script type="text/javascript">
	jQuery(document).ready(function()
	{
		// Disable click function on btn-group
		jQuery(".btn-group").each(function(index){
			if (jQuery(this).hasClass('disabled'))
			{
				jQuery(this).find("label").off('click');
			}
		});
	});

	/*
	 * Add form validation
	 */
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

		if ((pressbutton != 'object.close') && (pressbutton != 'object.cancel'))
		{
			if (document.formvalidator.isValid(form))
			{
				form.submit();
			}
		}
		else
		{
			form.submit();
		}
	}
</script>

<div class="dummy-edit-form">
	<form enctype="multipart/form-data"
		action="<?php echo JRoute::_('index.php?option=com_dummy&task=object.edit&id=' . $this->item->id . '&tmpl=' . $template);?>"
		method="post" name="adminForm" class="form-validate"
		id="adminForm">
		<hr />
		<div class="form-horizontal">
			<div class="row-fluid form-horizontal-desktop">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('name'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('lat', 'params'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('lat', 'params'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('lon', 'params'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('lon', 'params'); ?>
					</div>
				</div>

				<?php echo $this->form->getInput('id'); ?>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="jform[published]" value="1" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
</div>
