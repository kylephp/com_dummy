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
	  <div class="panel-heading">Customer Information</div>
	  <div class="panel-body">
	 		<div class="row">
		  		<div class="col-sm-6">
		  			<div class="form-group">
		  				<label class="control-label" for="firstname">
		  					First Name:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['firstname']?></p>
					    </div>
					    <label class="control-label" for="lastname">
		  					Last Name:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['lastname']?></p>
					    </div>
		  				<label class="control-label" for="email">
		  					Email:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['email']?></p>
					    </div>
					    <label class="control-label" for="gender">
		  					Gender:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['gender']?></p>
					    </div>
					    <label class="control-label" for="address">
		  					Address:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['address']?></p>
					    </div>
					    <label class="control-label" for="postcode">
		  					Postcode:
		  				</label>
		  				<div class="control-input">	
					      <p class="form-control-static"><?php echo $bblUser['postcode']?></p>
					    </div>
		  			</div>
		  		</div>
		  		<div class="col-sm-6">
		  			<div class="form-group">
			  			<label class="control-label" for="membernumber">
			  					Member Number:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['membernumber']?></p>
					    </div>
					    <label class="control-label" for="membershipcardnumber">
			  					Membership Card No.:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static"><?php echo $bblUser['membershipcardnumber']?></p>
					    </div>
				    </div>
		  		</div>
	  		</div>
	  </div>

	  <!-- Table Policies-->
	  <h3>My Policies</h3>
	  <table class="table">
	    <thead>
	      <tr>
	        <th>Policy Id</th>
	        <th>Contract Id</th>
	        <th>Partner Name</th>
	        <th>Product Name</th>
	        <th>Status</th>
	        <th>Object Name</th>
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
	  <h3>My Quotes</h3>
	  <table class="table">
	    <thead>
	      <tr>
	        <th>Quote Id</th>
	        <th>Quote Ref</th>
	        <th>Quote Name</th>
	        <th>Product Name</th>
	        <th>Status</th>
	        <th>Amount Gross</th>
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