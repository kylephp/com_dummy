<?php
defined('_JEXEC') or die;

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
					      <p class="form-control-static">Andreas Syse</p>
					    </div>
					    <label class="control-label" for="lastname">
		  					Last Name:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static">Andreas Syse</p>
					    </div>
		  			</div>
		  		</div>
		  		<div class="col-sm-6">
		  			<div class="form-group">
		  				<label class="control-label" for="email">
		  					Email:
		  				</label>
		  				<div class="control-input">
					      <p class="form-control-static">someone@example.com</p>
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
	      <tr>
	        <td>30052</th>
	        <td>130051</td>
	        <td>Vardia bil & boendeförsäkring</td>
	        <td>Hem</td>
	        <td>Active</td>
	        <td>Skeppsbrogatan 13 C Lgh 1002</td>
	      </tr>
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
	      <tr>
	        <td>20037</th>
	        <td>20037</td>
	        <td>Skeppsbrogatan 13 C Lgh 1002</td>
	        <td>Grupphem</td>
	        <td>Offered</td>
	        <td>948</td>
	      </tr>
	  </table>
	</div>
</div>