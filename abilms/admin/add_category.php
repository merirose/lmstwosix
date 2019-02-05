<?php 
session_start(); 
if(!isset($_SESSION["username"])){
	header("location: login.php");
}

include "../connection.php";
$msg="";
$msg_image="";
$category_id="";
$category_name="";
$category_image="";
$category_order="";
$top_category_id="";
function GetImageExtension($imagetype)
{
if(empty($imagetype))
		return false;
	switch($imagetype){
		case 'image/jpeg':
			return '.jpg';
		case 'image/png':
			return '.png';
		case 'image/bmp':
			return '.bmp';
		case 'image/gif':
			return '.gif';

		default:
			return false;
	}
}

if(isset($_GET["edit_id"])){

	$qe = mysqli_query($con, "select * from table_category where category_id='".mres($con,$_GET["edit_id"])."'");
	while($row=mysqli_fetch_array($qe)){
		$category_id=$row["category_id"];
		$category_name=$row["category_name"];
		$category_order=$row["category_order"];
		$top_category_id=$row["top_category_id"];
	}
}


if(isset($_POST["btn_save"])){
	$text_category_name=mres($con,$_POST["text_category_name"]);
	$text_category_order=mres($con,$_POST["text_category_order"]);
	$top_category_id=mres($con,$_POST["top_category_id"]);
	$qry=mysqli_query($con, "insert into table_category values('','".$text_category_name."','','".$text_category_order."','".$top_category_id."')");
	$category_id= mysqli_insert_id($con);

if($qry){
	$msg='
		<div id="login-alert" class="alert alert-success col-sm-12">Success! Data is inserted</div>
	';
		$file_name=$_FILES["text_category_image"]["name"];
		$temp_name=$_FILES["text_category_image"]["tmp_name"];
		$imgtype=$_FILES["text_category_image"]["type"];
		$ext= GetImageExtension($imgtype);
		if($ext !=false){
		$imagename=date('Y-m-d_h-i-s').rand(1111,9999).rand(1111,9999).$ext;
		$target_path= "../images/lectures/".$imagename;

		if(move_uploaded_file($temp_name, $target_path)){
			$qry_update=mysqli_query($con,"update table_category set category_image='".$imagename."' where category_id='".$category_id."'");
			$msg_image='
			<div id="login-alert" class="alert alert-success col-sm-12">Success! Image is inserted</div>';
		}
		else {
			$msg_image='
			<div id="login-alert" class="alert alert-danger col-sm-12">Fail! Image is not inserted</div>';
		}
		}
		else {
			$msg_image='
			<div id="login-alert" class="alert alert-danger col-sm-12">Fail! Image is not inserted</div>';
		}

}
else {
	$msg='
		<div id="login-alert" class="alert alert-danger col-sm-12">Fail! Cannot insert data to Database</div>
	';
}
}

if(isset($_POST["btn_edit"])){
$category_id=mres($con,$_POST["text_category_id"]);
$category_name=mres($con,$_POST["text_category_name"]);
$category_order=mres($con,$_POST["text_category_order"]);
$top_category_id=mres($con,$_POST["top_category_id"]);
$qry=mysqli_query($con,"update table_category set category_name='".$category_name."', category_order='".$category_order."',top_category_id='".$top_category_id."' where category_id='".$category_id."'");

if($qry){
	header("location: manage_category.php");
	$msg='
		<div id="login-alert" class="alert alert-success col-sm-12">Success! Data is edited</div>
	';
}
else {
	$msg='
		<div id="login-alert" class="alert alert-danger col-sm-12">Fail! Cannot edit data to Database</div>
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
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">Add New Category</div>
					</div>
						<div class="panel-body">
							<?php echo $msg;
							echo $msg_image;?>
							<form id="form_add_category" class="form-horizontal"
							role="form" method="post" action='<?php echo $_SERVER["PHP_SELF"];?>' enctype="multipart/form-data">
							<input type="hidden" name="text_category_id" value="<?php echo $category_id;?>">
							<div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon">Top Category Name</span>
                                    <input type="text" class="form-control" name="text_category_name" id="text_category_name" value="<?php echo $category_name;?>">                                    
                            </div>
							<div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon">Top Category Order</span>
                                    <input type="text" class="form-control" name="text_category_order" id="text_category_order" value="<?php echo $category_order;?>">
                                </div>

                                <div style="margin-bottom: 25px;" class="input-group">
                                	<span class="input-group-addon">Top Category</span>
                                	<select name="top_category_id" class="form-control" id="top_category_id">
                                		<option value=''>-- Choose a category --</option>
                                	<?php
                                	$qtc=mysqli_query($con,"select * from table_top_category order by top_category_order desc");
                                	while ($row=mysqli_fetch_array($qtc)) {
                                		if($row["top_category_id"]==$top_category_id)
                                		echo '<option value="'.$row["top_category_id"].'" selected>'.$row["top_category_name"].'</option>';
                                		else
                                		echo '<option value="'.$row["top_category_id"].'">'.$row["top_category_name"].'</option>';
                                	}

                                	?>
                                	</select></div>
									<div style="margin-bottom: 25px" class="input-group">
	                                    <span class="input-group-addon">Category Image</span>
	                                    <input type="file" class="form-control" name="text_category_image"
	                                    id="text_category_image" value="<?php echo $category_image;?>">
	                                </div>

                                <div style="margin-top:10px" class="form-group">
                                	<div class="col-sm-12 controls">
                                		<?php if(!isset($_GET["edit_id"])){
                                			echo '<input type="submit" id="btn_save" name="btn_save" class="btn btn-info btn-block btn-large" value="Save"></a>';

                                		}

 										else {
                                			echo '<input type="submit" id="btn_edit" name="btn_edit" class="btn btn-info btn-block btn-large" value="Edit"></a>';

 										}
 										?>
                                    </div>
                                </div>
						</form>

						</div>
			</div>



	</div>

</div>
<script>
	$(document).ready(function(){
			$('input[class="form-control"]').focus(function(){
			$(this).removeAttr('style');
			});

	  $("#btn_save,#btn_edit").click(function(e){
	 	if($('#text_category_name').val()==''){
	 		$('#text_category_name').css("border-color", "#DA190B");
	 		$('#text_category_name').css("background", "#F2DEDE");
	 		e.preventDefault();
	 	}
	 	if($('#text_category_order').val()==''){
	 		$('#text_category_order').css("border-color", "#DA190B");
	 		$('#text_category_order').css("background", "#F2DEDE");
	 		e.preventDefault();
	 	}
	 	if($('#top_category_id').val()==''){
	 		$('#top_category_id').css("border-color", "#DA190B");
	 		$('#top_category_id').css("background", "#F2DEDE");
	 		e.preventDefault();
	 	}
	 	if($('#text_category_image').val()==''){
	 		$('#text_category_image').css("border-color", "#DA190B");
	 		$('#text_category_image').css("background", "#F2DEDE");
	 		e.preventDefault();
	 	}
	 	else{
	 		$('form_add_top_category').unbind('submit').submit();
	 	}
	  });
	});
</script>
<?php include "footer.php"; ?>