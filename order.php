<?php
//user.php

include('database_connection.php');

// if(!isset($_SESSION["type"]))
// {
// 	header('location:login.php');
// }

// if($_SESSION["type"] != 'master')
// {
// 	header("location:index.php");
// }

include('header.php');


?>
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                        	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">Order List</h3>
                            </div>
							<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align ="right">
                            <button type="button" data-toggle="modal" data-target="#orderModal" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>    	
                        </div>
                        </div>
                       
                        <div class="clear:both"></div>
                   	</div>
                   	<div class="panel-body">
                   		<div class="row">
							<div class="col-sm-12 table-responsive">
                   			<table id="order_data" class="table table-bordered table-striped">
                   			<thead>
							<tr>
								<th>Order ID</th>
								<th>Customer Name</th>
								<th>Total Amount</th>
								<th>Payment Status</th>
								<th>Order Status</th>
								<th>Order Date</th>
								
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
                   			</table>
                   		</div>
                   	</div>
               	</div>
           	</div>
        </div>
        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog">
    		<form method="post" id="order_form">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Create Order</h4>
    				</div>
    				<div class="modal-body">
    					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Enter Receiver Name</label>
									<input type="text" name="inventory_order_name" id="inventory_order_name" class="form-control" required />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Date</label>
									<input type="text" name="inventory_order_date" id="inventory_order_date" class="form-control" required />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Enter Receiver Address</label>
							<textarea name="inventory_order_address" id="inventory_order_address" class="form-control" required></textarea>
						</div>
						<div class="form-group">
							<label>Enter Product Details</label>
							<hr />
							<span id="span_product_details"></span>
							<hr />
						</div>
						<div class="form-group">
							<label>Select Payment Status</label>
							<select name="payment_status" id="payment_status" class="form-control">
								<option value="cash">Cash</option>
								<option value="credit">Credit</option>
							</select>
						</div>
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="inventory_order_id" id="inventory_order_id" />
    					<input type="hidden" name="btn_action" id="btn_action" />
    					<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
    				</div>
    			</div>
    		</form>
    	</div>

    </div>
<script>
$(document).ready(function(){

	$('#add_button').click(function(){
			$('#orderModal').modal('show');
			$('#order_form')[0].reset();
			$('.modal-title').html("<i class='fa fa-plus'></i> Create Order");
			$('#action').val('Add');
			$('#btn_action').val('Add');
			$('#span_product_details').html('');
			add_product_row();
		});

	var userdataTable = $('#order_data').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax":{
			url:"order_fetch.php",
				type:"POST"
		},
		"columnDefs":[
			{
				"target":[4,5],
				"orderable":false
			}
		],
		"pageLength": 25
	});

	// dataTable Error for this
	// function add_product_row(count = '')
	// 	{
	// 		var html = '';
	// 		html += '<span id="row'+count+'"><div class="row">';
	// 		html += '<div class="col-md-8">';
	// 		html += '<select name="product_id[]" id="product_id'+count+'" class="form-control selectpicker" data-live-search="true" required>';
			// html += '';
	// 		html += '</select><input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" />';
	// 		html += '</div>';
	// 		html += '<div class="col-md-3">';
	// 		html += '<input type="text" name="quantity[]" class="form-control" required />';
	// 		html += '</div>';
	// 		html += '<div class="col-md-1">';
	// 		if(count == '')
	// 		{
	// 			html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
	// 		}
	// 		else
	// 		{
	// 			html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
	// 		}
	// 		html += '</div>';
	// 		html += '</div></div><br /></span>';
	// 		$('#span_product_details').append(html);

	// 		$('.selectpicker').selectpicker();
	// 	}

		// var count = 0;

	$(document).on('click', '#add_more', function(){
			count = count + 1;
			add_product_row(count);
		});
		$(document).on('click', '.remove', function(){
			var row_no = $(this).attr("id");
			$('#row'+row_no).remove();
		});

		$(document).on('submit', '#order_form', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"order_action.php",
				method:"POST",
				data:form_data,
				success:function(data){
					$('#order_form')[0].reset();
					$('#orderModal').modal('hide');
					$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
					$('#action').attr('disabled', false);
					orderdataTable.ajax.reload();
				}
			});
		});

		$(document).on('click', '.update', function(){
			var inventory_order_id = $(this).attr("id");
			var btn_action = 'fetch_single';
			$.ajax({
				url:"order_action.php",
				method:"POST",
				data:{inventory_order_id:inventory_order_id, btn_action:btn_action},
				dataType:"json",
				success:function(data)
				{
					$('#orderModal').modal('show');
					$('#inventory_order_name').val(data.inventory_order_name);
					$('#inventory_order_date').val(data.inventory_order_date);
					$('#inventory_order_address').val(data.inventory_order_address);
					$('#span_product_details').html(data.product_details);
					$('#payment_status').val(data.payment_status);
					$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Order");
					$('#inventory_order_id').val(inventory_order_id);
					$('#action').val('Edit');
					$('#btn_action').val('Edit');
				}
			})
		});

		$(document).on('click', '.delete', function(){
			var inventory_order_id = $(this).attr("id");
			var status = $(this).data("status");
			var btn_action = "delete";
			if(confirm("Are you sure you want to change status?"))
			{
				$.ajax({
					url:"ordar_action.php",
					method:"POST",
					data:{inventory_order_id:inventory_order_id, status:status, btn_action:btn_action},
					success:function(data)
					{
						$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
						orderdataTable.ajax.reload();
					}
				})
			}
			else
			{
				return false;
			}
		});

});
</script>

<?php
include('footer.php');
?>
