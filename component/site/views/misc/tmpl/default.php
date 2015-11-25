<?php
defined('_JEXEC') or die;
extract($this->data);
$bblUser = $bbl->getBblUserInfo();
?>
<style>
	.form-group label {
	    font-weight: 700;
	    font-size: 16px;
	}
</style>
<div class="container-fluid">
	<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading"><?php echo JText::_('COM_DUMMY_MISC_CUSTOMER_INFORMATION')?></div>
	  <div class="panel-body">
	 		<div class="row">
		  		<div class="col-sm-6">
		  			<div class="form-group">
		  				<label class="control-label" for="firstname">
		  					<?php echo JText::_('COM_DUMMY_MISC_FIRSTNAME')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['firstname']?></p>
					    </div>
					    <label class="control-label" for="lastname">
		  					<?php echo JText::_('COM_DUMMY_MISC_LASTNAME')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['lastname']?></p>
					    </div>
		  				<label class="control-label" for="email">
		  					<?php echo JText::_('COM_DUMMY_MISC_EMAIL')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['email']?></p>
					    </div>
					    <label class="control-label" for="gender">
		  					<?php echo JText::_('COM_DUMMY_MISC_GENDER')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['gender']?></p>
					    </div>
		  			</div>
		  		</div>
		  		<div class="col-sm-6">
		  			<div class="form-group">
			  			<label class="control-label" for="membernumber">
			  				<?php echo JText::_('COM_DUMMY_MISC_MEMBER_NUMBER')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['membernumber']?></p>
					    </div>
					    <label class="control-label" for="membershipcardnumber">
			  				<?php echo JText::_('COM_DUMMY_MISC_MEMBERSHIP_CARD_NO')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['membershipcardnumber']?></p>
					    </div>
			    	    <label class="control-label" for="address">
		  					<?php echo JText::_('COM_DUMMY_MISC_ADDRESS')?>
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['address']?></p>
					    </div>
					    <label class="control-label" for="postcode">
		  					<?php echo JText::_('COM_DUMMY_MISC_POSTCODE')?>
		  				</label>
		  				<div class="control-input">	
					      <p class="form-control-static"><?php echo $bblUser['postcode']?></p>
					    </div>
				    </div>
		  		</div>
	  		</div>
	  </div>

	  <!-- Table Policies-->
	  <h3><?php echo JText::_('COM_DUMMY_MISC_MY_POLICIES')?></h3>
	  <table class="table">
	    <thead>
	      <tr>
	        <th><?php echo JText::_('COM_DUMMY_MISC_POLICY_ID')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_CONTRACT_ID')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_PARTNER_NAME')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_PRODUCT_NAME')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_STATUS')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_OBJECT_NAME')?></th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php foreach ($policies as $policy):?>
	      <tr>
	        <td><?php echo $policy['policyId']?></th>
	        <td><?php echo $policy['contractId']?></td>
	        <td><?php echo $policy['partnerName']?></td>
	        <td><?php echo $policy['productName']?></td>
	        <td><?php echo $policy['status']?></td>
	        <td><?php echo $policy['objectName']?></td>
	      </tr>
	     <?php endforeach; ?>
	  </table>

	  <!-- Table Quotes-->
	  <h3><?php echo JText::_('COM_DUMMY_MISC_MY_QUOTES')?></h3>
	  <table class="table">
	    <thead>
	      <tr>
	        <th><?php echo JText::_('COM_DUMMY_MISC_QUOTE_ID')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_QUOTE_REF')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_QUOTE_NAME')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_PRODUCT_NAME')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_STATUS')?></th>
	        <th><?php echo JText::_('COM_DUMMY_MISC_AMOUNT_GROSS')?></th>
	      </tr>
	    </thead>
	    <tbody>
	      <?php foreach ($quotes as $quote):?>
	      <tr>
	        <td><?php echo $quote['quoteId']?></th>
	        <td><?php echo $quote['quoteReference']?></td>
	        <td><?php echo $quote['quoteName']?></td>
	        <td><?php echo $quote['productName']?></td>
	        <td><?php echo $quote['status']?></td>
	        <td><?php echo $quote['amountGross']?></td>
	      </tr>
	     <?php endforeach; ?>
	  </table>
	</div>
</div>