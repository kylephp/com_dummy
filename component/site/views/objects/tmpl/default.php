<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$jsLibrary = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places';

// If this library not load, load it
if (!array_key_exists($jsLibrary, $doc->_scripts))
{
	$doc->addScript($jsLibrary);
}

RHelperAsset::load('googlemaps/markerwithlabel.min.js', 'com_dummy');
RHelperAsset::load('googlemaps/infobubble.min.js', 'com_dummy');
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
$input = JFactory::getApplication()->input;
$template = $input->getString('tmpl');
?>
<script type="text/javascript">
	var map = null;
	var markers = new Array();
	var mapBounds = new google.maps.LatLngBounds();
	var circleOptions = null;
	var circleSetting = null;
	var centerPoint = {lat:159.921667, lng:10.752839};

	function objectsGmapInitialize()
	{
		myOptions = {
			zoom: 12,
			center: centerPoint,
			panControl: false,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
			draggable: true,
			scrollwheel: true,
			disableDoubleClickZoom: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			}
		};

		map = new google.maps.Map(document.getElementById("objectsGmapCanvas"), myOptions);

		<?php if (!empty($this->items)) : ?>
			<?php $markersCount = 0; ?>
			<?php foreach ($this->items as $item) : ?>
				<?php $params = new JRegistry($item->params); ?>
				<?php if (!empty($params->get('lat')) && !empty($params->get('lon'))) : ?>
					var coordinate =  {lat: <?php echo $params->get('lat')?>, lng: <?php echo $params->get('lon')?>};
					objectsGmapAddMarker(
						map,
						coordinate,
						'<?php echo $item->name; ?>',
						'gmap_labels',
						'http://stanfordup.googlecode.com/svn/trunk/StanfordEvents/Pin.png',
						<?php echo $item->id; ?>,
						<?php echo json_encode($item->name); ?>
					);
					<?php $markersCount++; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if ($markersCount > 0) : ?>
			map.fitBounds(mapBounds);
			<?php else : ?>
			map.panTo(centerPoint);
			<?php endif; ?>
		<?php endif; ?>
	}

	function objectsGmapMarkerRemake(lbl)
	{
		for (var i = 0; i < markers.length; i++)
		{
			if ((lbl != '') && (markers[i].labelContent == lbl))
			{
				markers[i].labelClass = 'gmap_active';
			}
			else
			{
				markers[i].labelClass = 'gmap_labels';
			}

			markers[i].label.setStyles();
		}
	}

	function objectsGmapAddMarker(map, markerLatLng, markerTitle, markerClass, pinIcon, index, inforbox)
	{
		var location = new google.maps.LatLng(parseFloat(markerLatLng.lat), parseFloat(markerLatLng.lng));
		mapBounds.extend(location);

		var marker = new MarkerWithLabel({
			position: location,
			draggable: false,
			raiseOnDrag: false,
			map: map,
			labelContent: markerTitle,
			labelAnchor: new google.maps.Point(28, 0),
			labelClass: markerClass,
			labelStyle: {opacity: 1.0},
			icon: pinIcon
		});

		// Create information box
		var iw = new InfoBubble({
			content: inforbox
		});

		google.maps.event.addListener(marker, "click", function (e) {
			// objectsGmapMarkerRemake(markerTitle);
			iw.open(map, marker);
		});

		google.maps.event.addListener(marker, "mouseover", function (e) {
			if (this.labelClass != 'gmap_active')
			{
				this.labelClass = 'gmap_active';
				this.label.setStyles();
			}
		});

		google.maps.event.addListener(marker, "mouseout", function (e) {
			if (this.labelClass != 'gmap_labels')
			{
				this.labelClass = 'gmap_labels';
				this.label.setStyles();
			}
		});

		markers[index] = marker;
	}

	/**
	 * Method for draw a circle on map
	 *
	 * @param   GoogleMapPoint  locationPoint  Location point for center of circle
	 * @param   int             distanceValue  Distance radius for this circle (in meters)
	 *
	 * @return  void
	 */
	function objectsGmapDrawCircle(locationPoint, distanceValue)
	{
		if (circleSetting != null)
		{
			// Clear old circle
			circleSetting.setMap(null);
		}

		circleOptions = {
			strokeColor: '#FFFFFF',
			strokeOpacity: 0.7,
			strokeWeight: 2,
			fillColor: '#808080',
			fillOpacity: 0.5,
			map: map,
			center: locationPoint,
			radius: distanceValue
		};

		// Add the circle to the map.
		circleSetting = new google.maps.Circle(circleOptions);
		map.fitBounds(circleSetting.getBounds());
		map.panTo(locationPoint);
	}

	/**
	 * Method for remove a circle on map
	 *
	 * @return  void
	 */
	function objectsGmapRemoveCircle()
	{
		if (circleSetting != null)
		{
			// Clear old circle
			circleSetting.setMap(null);
		}

		map.fitBounds(mapBounds);
		map.panToBounds(mapBounds);
	}

	google.maps.event.addDomListener(window, 'load', objectsGmapInitialize);
</script>

<div class="row">
	<div class="col-sm-4">
		<div id="objectsGmapCanvas" style="width: 100%; height: 500px;"></div>
	</div>
	<div class="col-sm-8">
		<form action="<?php echo JRoute::_('index.php?option=com_dummy&view=objects')?>" class="admin" id="adminForm" method="post" name="adminForm">
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
						<?php if ($search == '') : ?>
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
								<?php echo JHtml::_('link', 'index.php?option=com_dummy&task=object.edit&id=' . $item->id . '&tmpl=' . $template, $itemTitle); ?>
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
	</div>
</div>

