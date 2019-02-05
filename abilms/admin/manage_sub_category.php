<?php 
session_start(); 
if(!isset($_SESSION["username"])){
	header("location: login.php");
}

include "../connection.php";
$msg="";
if(isset($_GET["delete_id"])){
// $text_top_category_name=mres($con,$_POST["text_top_category_name"]);
// $text_top_category_order=mres($con,$_POST["text_top_category_order"]);
$qry=mysqli_query($con, "delete from table_sub_category where sub_category_id='".mres($con,$_GET["delete_id"])."'");
if($qry){
	$msg='
		<div id="login-alert" class="alert alert-success col-sm-12">Success! Data is deleted</div>
	';
}
else {
	$msg='
		<div id="login-alert" class="alert alert-danger col-sm-12">Fail! Cannot delete data</div>
	';
}
}
?>
<?php
include "header.php";

?>
	<div class="row" style="padding-left: 0px;padding-right: 0px;">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
				<?php include "left_menu.php";?>
			</div>

			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
				<div class="well">
					<form method="post" class="form-inline" id="form_search" action='<?php echo $_SERVER["PHP_SELF"];?>'>
					  <div class="form-group">
					    <label>Search By Name:</label>
					   		<input type="text" class="form-control" id="search_text" name="search_text">
					  </div>
  					<button type="submit" class="btn btn-default" id="btn_search" name="btn_search">Search</button>
					</form>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">Manage Sub Category</div>
					</div>

						<div class="panel-body">
							<?php echo $msg;?>
			  <table class="table table-hover table-bordered">
			    <thead>
			      <tr>
			        <th>#</th>
			        <th>Sub Category Name</th>
			        <th>Sub Category Order</th>
			        <th>Category Name</th>
			        <th>Action</th>
			      </tr>
			    </thead>
			    <tbody>
			    	<?php
			    	$qry="";
			    	if(isset($_POST["btn_search"])){
			    		$qry=mysqli_query($con,"select * from table_sub_category
			    		inner join table_category on table_sub_category.category_id = table_category.category_id
			    		where sub_category_name like '%".mres($con,$_POST["search_text"])."%'
						order by category_order asc");

			    	}

			    	else {
			    		$qry=mysqli_query($con,"select * from table_sub_category
			    		inner join table_category on table_sub_category.category_id = table_category.category_id order by category_order asc");		
			    	}
			    	while($row=mysqli_fetch_array($qry)) {
			    		echo '<tr>';
			    		echo '<td>'.$row["sub_category_id"]."</td><td>".$row["sub_category_name"].
			    		"</td><td>".$row["sub_category_order"]."</td><td>".$row["category_name"]."</td><td>
			    		<a href='add_sub_category.php?edit_id=".$row["sub_category_id"]."'> Edit </a>| <a href='?delete_id=".$row["sub_category_id"]."' onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</a></td>";
			    		echo '</tr>';
			    	}
			    	?>

			    </tbody>
			  </table>


						</div>
		</div>

	</div>

</div>
<script>
	$(document).ready(function(){

	  $("#btn_search").click(function(e){
	 	if($('#search_text').val()==''){
	 		$('#search_text').css("border-color", "#DA190B");
	 		$('#search_text').css("background", "#F2DEDE");
	 		e.preventDefault();
	 	}
	 	else{
	 		$('form_search').unbind('submit').submit();
	 	}
	  });

	  // $("#btn_password").click(function(e){
	 	// if($('#password').val()==''){
	 	// 	$('#password').css("border-color", "#DA190B");
	 	// 	$('#password').css("background", "#F2DEDE");
	 	// 	e.preventDefault();
	 	// }
	 	// else{
	 	// 	$('form_password').unbind('submit').submit();
	 	// }
	  // });

	});
</script>
<?php include "footer.php"; ?>