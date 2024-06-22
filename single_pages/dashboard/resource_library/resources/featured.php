<?php
defined('C5_EXECUTE') or die('Access Denied.');
$pageSelector = Loader::helper('form/page_selector');
$featuredIds = [];
?>
 
<style>
   #featured_resources_area{
      border: 1px solid black;
   }
   #featured_resources_area .insight_row{
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      margin-bottom: 10px;
   }
   #featured_resources_area .insight_row:last-child{
      margin-bottom: 0;
   }
   #featured_resources_area .insight_row .column{
      margin-right: 3.5%;
   }
   #featured_resources_area .insight_row .resource_box{
      background-color: rgb(186, 186, 186);
      box-sizing: border-box;
       -moz-box-sizing: border-box;
       -webkit-box-sizing: border-box;
       cursor: pointer;
       padding: 10px;
       overflow: hidden;
   }
   #featured_resources_area .insight_row .resource_box p{
      margin: 0;
      font-size: 14px;
   }
   #featured_resources_area .insight_row .resource_box.active{
    border: 5px solid rgb(0, 183, 0);  
   }
   #featured_resources_area .insight_row .height_1{
      height: 95px;
      margin-bottom: 10px;
   }
   #featured_resources_area .insight_row .height_2{
      height: 200px;
      margin-bottom: 0;
   }
   #featured_resources_area .insight_row .span_1{
      width: 31%;
   }
   #featured_resources_area .insight_row .span_2{
      width: 65.5%;
   }
   #featured_resources_area .insight_row .span_3{
      width: 100%;
      margin-right: 0;
   }
   #featured_resources_area .insight_row .noright{
      margin-right: 0;
   }
   #featured_resources_area .insight_row .nobottom{
      margin-bottom: 0;
   }
</style>

<p>Up to twelve resources can be placed at the top of the Insights page list. Please click one of the boxes below to add or edit a featured insight. Please note the resources will not be saved until you click the Save button at the bottom.</p>
<form method="post" enctype="multipart/form-data" id="featured_resources_list" action="<?php echo View::action(
	'featuredsave'
); ?>">
<div id="featured_resources_area">
   <div class="insight_row">
      <div class="column span_2">
         <div class="resource_box height_2" data-id="1">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_1) &&
            	strlen($featuredinsightname_1)
            ) {
            	echo $featuredinsightname_1;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_1) &&
            	strlen($featuredinsightcolor_1)
            ) {
            	if ($featuredinsightcolor_1 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_1 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_1) &&
            	strlen($featuredinsightimage_1)
            ) {
            	if ($featuredinsightimage_1 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_1 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_1 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_1 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_1" value="<?php echo $featuredinsightname_1; ?>">
            <input type="hidden" name="featuredinsightid_1" value="<?php echo $featuredinsightid_1; ?>">
            <input type="hidden" name="featuredinsightcolor_1" value="<?php echo $featuredinsightcolor_1; ?>">
            <input type="hidden" name="featuredinsightimage_1" value="<?php echo $featuredinsightimage_1; ?>">
         </div>
      </div>
      <div class="column span_1 noright">
         <div class="resource_box height_2 nobottom" data-id="2">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_2) &&
            	strlen($featuredinsightname_2)
            ) {
            	echo $featuredinsightname_2;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_2) &&
            	strlen($featuredinsightcolor_2)
            ) {
            	if ($featuredinsightcolor_2 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_2 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_2) &&
            	strlen($featuredinsightimage_2)
            ) {
            	if ($featuredinsightimage_2 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_2 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_2 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_2 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_2" value="<?php echo $featuredinsightname_2; ?>">
            <input type="hidden" name="featuredinsightid_2" value="<?php echo $featuredinsightid_2; ?>">
            <input type="hidden" name="featuredinsightcolor_2" value="<?php echo $featuredinsightcolor_2; ?>">
            <input type="hidden" name="featuredinsightimage_2" value="<?php echo $featuredinsightimage_2; ?>">
         </div>
      </div>
   </div>
   <div class="insight_row">
      <div class="column span_1">
         <div class="resource_box height_2" data-id="3">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_3) &&
            	strlen($featuredinsightname_3)
            ) {
            	echo $featuredinsightname_3;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_3) &&
            	strlen($featuredinsightcolor_3)
            ) {
            	if ($featuredinsightcolor_3 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_3 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_3) &&
            	strlen($featuredinsightimage_3)
            ) {
            	if ($featuredinsightimage_3 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_3 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_3 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_3 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_3" value="<?php echo $featuredinsightname_3; ?>">
            <input type="hidden" name="featuredinsightid_3" value="<?php echo $featuredinsightid_3; ?>">
            <input type="hidden" name="featuredinsightcolor_3" value="<?php echo $featuredinsightcolor_3; ?>">
            <input type="hidden" name="featuredinsightimage_3" value="<?php echo $featuredinsightimage_3; ?>">
         </div>
      </div>
      <div class="column span_1">
         <div class="resource_box height_2" data-id="4">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_4) &&
            	strlen($featuredinsightname_4)
            ) {
            	echo $featuredinsightname_4;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_4) &&
            	strlen($featuredinsightcolor_4)
            ) {
            	if ($featuredinsightcolor_4 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_4 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_4) &&
            	strlen($featuredinsightimage_4)
            ) {
            	if ($featuredinsightimage_4 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_4 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_4 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_4 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_4" value="<?php echo $featuredinsightname_4; ?>">
            <input type="hidden" name="featuredinsightid_4" value="<?php echo $featuredinsightid_4; ?>">
            <input type="hidden" name="featuredinsightcolor_4" value="<?php echo $featuredinsightcolor_4; ?>">
            <input type="hidden" name="featuredinsightimage_4" value="<?php echo $featuredinsightimage_4; ?>">
         </div>
      </div>
      <div class="column span_1 noright">
         <div class="resource_box height_1" data-id="5">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_5) &&
            	strlen($featuredinsightname_5)
            ) {
            	echo $featuredinsightname_5;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_5) &&
            	strlen($featuredinsightcolor_5)
            ) {
            	if ($featuredinsightcolor_5 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_5 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_5) &&
            	strlen($featuredinsightimage_5)
            ) {
            	if ($featuredinsightimage_5 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_5 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_5 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_5 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_5" value="<?php echo $featuredinsightname_5; ?>">
            <input type="hidden" name="featuredinsightid_5" value="<?php echo $featuredinsightid_5; ?>">
            <input type="hidden" name="featuredinsightcolor_5" value="<?php echo $featuredinsightcolor_5; ?>">
            <input type="hidden" name="featuredinsightimage_5" value="<?php echo $featuredinsightimage_5; ?>">
         </div>
         <div class="resource_box height_1 nobottom" data-id="6">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_6) &&
            	strlen($featuredinsightname_6)
            ) {
            	echo $featuredinsightname_6;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_6) &&
            	strlen($featuredinsightcolor_6)
            ) {
            	if ($featuredinsightcolor_6 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_6 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_6) &&
            	strlen($featuredinsightimage_6)
            ) {
            	if ($featuredinsightimage_6 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_6 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_6 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_6 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_6" value="<?php echo $featuredinsightname_6; ?>">
            <input type="hidden" name="featuredinsightid_6" value="<?php echo $featuredinsightid_6; ?>">
            <input type="hidden" name="featuredinsightcolor_6" value="<?php echo $featuredinsightcolor_6; ?>">
            <input type="hidden" name="featuredinsightimage_6" value="<?php echo $featuredinsightimage_6; ?>">
         </div>
      </div>
   </div>
   <div class="insight_row">
      <div class="column span_1">
         <div class="resource_box height_2" data-id="7">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_7) &&
            	strlen($featuredinsightname_7)
            ) {
            	echo $featuredinsightname_7;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_7) &&
            	strlen($featuredinsightcolor_7)
            ) {
            	if ($featuredinsightcolor_7 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_7 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_7) &&
            	strlen($featuredinsightimage_7)
            ) {
            	if ($featuredinsightimage_7 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_7 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_7 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_7 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_7" value="<?php echo $featuredinsightname_7; ?>">
            <input type="hidden" name="featuredinsightid_7" value="<?php echo $featuredinsightid_7; ?>">
            <input type="hidden" name="featuredinsightcolor_7" value="<?php echo $featuredinsightcolor_7; ?>">
            <input type="hidden" name="featuredinsightimage_7" value="<?php echo $featuredinsightimage_7; ?>">
         </div>
      </div>
      <div class="column span_2 noright">
         <div class="resource_box height_2 nobottom" data-id="8">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_8) &&
            	strlen($featuredinsightname_8)
            ) {
            	echo $featuredinsightname_8;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_8) &&
            	strlen($featuredinsightcolor_8)
            ) {
            	if ($featuredinsightcolor_8 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_8 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_8) &&
            	strlen($featuredinsightimage_8)
            ) {
            	if ($featuredinsightimage_8 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_8 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_8 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_8 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_8" value="<?php echo $featuredinsightname_8; ?>">
            <input type="hidden" name="featuredinsightid_8" value="<?php echo $featuredinsightid_8; ?>">
            <input type="hidden" name="featuredinsightcolor_8" value="<?php echo $featuredinsightcolor_8; ?>">
            <input type="hidden" name="featuredinsightimage_8" value="<?php echo $featuredinsightimage_8; ?>">
         </div>
      </div>
   </div>
   <div class="insight_row">
      <div class="column span_1">
         <div class="resource_box height_1" data-id="9">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_9) &&
            	strlen($featuredinsightname_9)
            ) {
            	echo $featuredinsightname_9;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_9) &&
            	strlen($featuredinsightcolor_9)
            ) {
            	if ($featuredinsightcolor_9 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_9 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_9) &&
            	strlen($featuredinsightimage_9)
            ) {
            	if ($featuredinsightimage_9 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_9 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_9 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_9 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_9" value="<?php echo $featuredinsightname_9; ?>">
            <input type="hidden" name="featuredinsightid_9" value="<?php echo $featuredinsightid_9; ?>">
            <input type="hidden" name="featuredinsightcolor_9" value="<?php echo $featuredinsightcolor_9; ?>">
            <input type="hidden" name="featuredinsightimage_9" value="<?php echo $featuredinsightimage_9; ?>">
         </div>
         <div class="resource_box height_1 nobottom" data-id="10">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_10) &&
            	strlen($featuredinsightname_10)
            ) {
            	echo $featuredinsightname_10;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_10) &&
            	strlen($featuredinsightcolor_10)
            ) {
            	if ($featuredinsightcolor_10 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_10 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_10) &&
            	strlen($featuredinsightimage_10)
            ) {
            	if ($featuredinsightimage_10 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_10 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_10 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_10 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_10" value="<?php echo $featuredinsightname_10; ?>">
            <input type="hidden" name="featuredinsightid_10" value="<?php echo $featuredinsightid_10; ?>">
            <input type="hidden" name="featuredinsightcolor_10" value="<?php echo $featuredinsightcolor_10; ?>">
            <input type="hidden" name="featuredinsightimage_10" value="<?php echo $featuredinsightimage_10; ?>">
         </div>
      </div>
      <div class="column span_1">
         <div class="resource_box height_2" data-id="11">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_11) &&
            	strlen($featuredinsightname_11)
            ) {
            	echo $featuredinsightname_11;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_11) &&
            	strlen($featuredinsightcolor_11)
            ) {
            	if ($featuredinsightcolor_11 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_11 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_11) &&
            	strlen($featuredinsightimage_11)
            ) {
            	if ($featuredinsightimage_11 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_11 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_11 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_11 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_11" value="<?php echo $featuredinsightname_11; ?>">
            <input type="hidden" name="featuredinsightid_11" value="<?php echo $featuredinsightid_11; ?>">
            <input type="hidden" name="featuredinsightcolor_11" value="<?php echo $featuredinsightcolor_11; ?>">
            <input type="hidden" name="featuredinsightimage_11" value="<?php echo $featuredinsightimage_11; ?>">
         </div>
      </div>
      <div class="column span_1 noright">
         <div class="resource_box height_2" data-id="12">
            <p class="name"><b>Name: </b><span><?php if (
            	isset($featuredinsightname_12) &&
            	strlen($featuredinsightname_12)
            ) {
            	echo $featuredinsightname_12;
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="color"><b>Color: </b><span><?php if (
            	isset($featuredinsightcolor_12) &&
            	strlen($featuredinsightcolor_12)
            ) {
            	if ($featuredinsightcolor_12 == 1) {
            		echo 'White';
            	}
            	if ($featuredinsightcolor_12 == 2) {
            		echo 'Gray';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <p class="image"><b>Image: </b><span><?php if (
            	isset($featuredinsightimage_12) &&
            	strlen($featuredinsightimage_12)
            ) {
            	if ($featuredinsightimage_12 == 1) {
            		echo 'None';
            	}
            	if ($featuredinsightimage_12 == 2) {
            		echo 'Icon';
            	}
            	if ($featuredinsightimage_12 == 3) {
            		echo 'Thumbnail Image';
            	}
            	if ($featuredinsightimage_12 == 4) {
            		echo 'Full Background Image';
            	}
            } else {
            	echo 'N/A';
            } ?></span></p>
            <input type="hidden" name="featuredinsightname_12" value="<?php echo $featuredinsightname_12; ?>">
            <input type="hidden" name="featuredinsightid_12" value="<?php echo $featuredinsightid_12; ?>">
            <input type="hidden" name="featuredinsightcolor_12" value="<?php echo $featuredinsightcolor_12; ?>">
            <input type="hidden" name="featuredinsightimage_12" value="<?php echo $featuredinsightimage_12; ?>">
         </div>
      </div>
   </div>
</div>
<br>
<a href="/dashboard/resource_library/resources" class="btn btn-danger">Cancel</a>
<input type="submit" class="btn btn-primary" value="Save All Featured Insights">
</form>
 
 <style>
     #insight_filters{
         list-style: none;
         margin: 0;
         padding: 0;
         display: flex;
         flex-direction: row;
         flex-wrap: wrap;
     }
     #insight_filters li{
         width: 30%;
         margin-right: 5%;
     }
     #insight_filters li:last-child{
         margin-right: 0;
     }
     #insight_filters li select{
         width: 100%;
         height: 50px;
     }
     #insight_filters li input[type="text"]{
         width: calc(100% - 110px);
         margin-right: 10px;
         height: 50px;
         box-sizing: border-box;
     }
     #insight_filters li input[type="text"] + button{
         width: 95px;
         height: 50px;
     }
     #resources_list{
         display: none;
         list-style: none;
         margin: 0;
         padding: 0;
         border: 1px solid black;
         border-radius: 3px;
         margin-top: 10px;
     }
     #resources_list li{
         border-bottom: 1px solid black;
     }
     #resources_list li:last-child{
         border-bottom: 0;
     }
     #resources_list li a{
         cursor: pointer;
         font-size: 16px;
         width: 100%;
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 5px 15px;
     }
     #resources_list li a:hover{
         text-decoration: none;
         background-color: #ddd;
     }
     #resources_list li a span.plus{
         font-size: 20px;
         font-weight: 700;
     }
     #featured_resource_options{
          list-style: none;
          margin: 2.5em 0;
          padding: 0;
          border: 1px solid black;
          border-radius: 3px;
          margin-top: 10px;
     }
     #featured_resource_options .resource_item{
          border-bottom: 1px solid black;
          padding: 5px 15px;
          display: flex;
          align-items: center;
          justify-content: space-between
      }
      #featured_resource_options .resource_item{
          border-bottom: 0;
      }
      #featured_resource_options .resource_item a{
          cursor: pointer;
      }
      .resource_search_holder{
         display: none;
         margin-top: 15px;
      }
      .resource_options{
         display: none;
         margin-top: 15px;
      }
 </style>


<div class="resource_search_holder">
   <p>Use the search below to find the resource you are looking for. You may then add it to the list below. Below you may add, edit, delete, and re-order the feature resources.</p>
   
   <ul id="insight_filters">
       <li>
           <form id="resource_search">
               <input lass="form-control" type="text" placeholder="What are you looking for?">
               <button type="submit" class="submit">Search</button>
           </form>
       </li>
       <li class="select">
           <select lass="form-control" name="type" id="type">
               <option value="0">Content Type</option>
               <?php foreach ($types as $type) {
               	echo '<option value="' .
               		$type['id'] .
               		'">' .
               		$type['typeName'] .
               		'</option>';
               } ?>
           </select>
       </li>
   </ul>
   
   <ul id="resources_list">
       
   </ul>
   
   <div class="resource_options">
      <hr>
      <h3>Resource Options</h3>
      <div class="form-group">
         <label class="form-control" for="resourcechosen_name">Resource Name</label>
         <input class="form-control" type="text" name="resourcechosen_name" id="resourcechosen_name" value="Test Resource" readonly="readonly">
         <input type="hidden" name="resourcechosen_id" id="resourcechosen_id" value="">
      </div>
      <div class="form-group">
         <label class="form-control" for="resourcechosen_color">Resource Background Color</label>
         <select class="form-control" name="resourcechosen_color" id="resourcechosen_color">
            <option value="1">White</option>
            <option value="2">Gray</option>
         </select>
      </div>
      <div class="form-group">
         <label class="form-control" for="resourcechosen_image">Resource Image</label>
         <select class="form-control" name="resourcechosen_image" id="resourcechosen_image">
            <option value="1">None</option>
            <option value="2">Icon</option>
            <option value="3">Thumbnail Image</option>
            <option value="4">Full Background Image</option>
         </select>
      </div>
      <a class="cancel_insight_edit btn btn-danger">Cancel Changes</a>
      <a class="add_insight_to_board btn btn-primary">Update Insight</a>
   </div>
</div>

<script>
    featuredItems = [];
    function performFilter(){
        searchQuery = $( "#insight_filters input[type='text']").val();
        contentTypeSearch = $("#insight_filters select#type").val();
        topicSearch = $("#insight_filters select#topic").val();
        resourceList = $("#resources_list");
        resourceOptions = $("#featured_resource_options");
        if(searchQuery == "" && contentTypeSearch == 0 && topicSearch == 0){
           //NO RESULTS
           resourceList.hide();
        } else {
           
           searchObj = {
              query: searchQuery,
               type: contentTypeSearch,
               topic: topicSearch,
               count: 99999999,
               offset: 0
           };
           fetch('/resources/find', {
              method: 'POST',
              headers: {
                 Accept: 'application/json',
                 'Content-Type': 'application/json'
              },
              body: JSON.stringify(searchObj)
           })
              .then(response => response.json())
              .then(response => showNewResources(response));
           
           
            searchObj = {
                query: searchQuery,
                type: contentTypeSearch,
                topic: topicSearch,
                count: 99999999,
                offset: 0
            };
        }
    }
    function showNewResources(data){
       // console.log(data);
        resourceList.show();
        resourceList.html("");
        $('html, body').animate({
             scrollTop: $("#resources_list").offset().top
         }, 300);
        // $("#resources_list .resource").removeClass("active");
        data.forEach(data => {
            resourceList.append("<li><a class='additem'><span class='title' data-id='"+data.id+"'>"+data.name+"</span><span class='plus'>+</span></a></li>");
            Object.entries(data).forEach(([key, value]) => {
                // resourceList.append("<li><span></span></li>");
                if(key == "id"){
                    // $("#resources_list a.resource[data-id='"+value+"']").addClass("active");
                }
            });
        });
    }
    $( "form#resource_search" ).submit(function(e) {
        e.preventDefault();
        performFilter();
    });
    $('#insight_filters select').on('change', function() {
        performFilter();
    });
    $("body").on("click", "#resources_list a", function (e) {
        e.preventDefault();
        elem = $(this);  
        $(".resource_options #resourcechosen_name").val(elem.children("span.title").text());
        $(".resource_options #resourcechosen_id").val(elem.children("span.title").data("id"));
        $(".resource_options #resourcechosen_color").val(1);
        $(".resource_options #resourcechosen_image").val(1);
        $(".resource_options").slideDown("fast");
        $('html, body').animate({
             scrollTop: $(".resource_options").offset().top
         }, 300);
        // if(resourceOptions.children(".resource_item").length >= 13){
        //     alert("You cannot have more than eight featured resources. Please remove one to add a new featured resource.");
        // } else {
        //     elem = $(this);  
        //     grid.addWidget({w: 4, h: 1, maxW: 9, minW: 4, maxH: 4, minH: 1, id: elem.children("span.title").data("id"), content: ''+elem.children("span.title").text()+''});
        //     resourceOptions.append('<div class="resource_item" data-id="'+elem.children("span.title").data("id")+'"><a class="mover"><i class="fa fa-arrows" aria-hidden="true"></i></a><span>'+elem.children("span.title").text()+'</span><a class="remove">X</a></div>');  
        //     gatherResources();
        // }
    });
    $("body").on("click", ".resource_item a.remove", function (e) {
        $(this).parent(".resource_item").remove();
        gatherResources();
    });
    function gatherResources(){
        featuredItems = [];
        for(x=0;x<$(".resource_item").length;x++){
            thisId = $(".resource_item").eq(x).data("id");
            featuredItems.push(thisId);
            $("#featured_items").val(featuredItems.toString());
        }
        if($(".resource_item").length == 0){
            $("#featured_items").val("");
        }
    }
    $('#featured_resources_area .resource_box').on('click', function() {
       $('#featured_resources_area .resource_box').removeClass("active");
       $(this).addClass("active");
      thisId = $(this).data("id");
      if($(this).find('input[name="featuredinsightid_'+thisId+'"]').val() > 0){
         $(".resource_options").slideDown("fast");
         $(".resource_options #resourcechosen_name").val($(this).find('input[name="featuredinsightname_'+thisId+'"]').val());
           $(".resource_options #resourcechosen_id").val($(this).find('input[name="featuredinsightid_'+thisId+'"]').val());
           $(".resource_options #resourcechosen_color").val($(this).find('input[name="featuredinsightcolor_'+thisId+'"]').val());
           $(".resource_options #resourcechosen_image").val($(this).find('input[name="featuredinsightimage_'+thisId+'"]').val());
           $('html, body').animate({
                scrollTop: $(".resource_options").offset().top
            }, 300);
      } else {
         $(".resource_options").hide();
         $('html, body').animate({
         scrollTop: $(".resource_search_holder").offset().top
         }, 300);
      }
      $(".resource_search_holder").slideDown("fast");
    });
    $('.add_insight_to_board').on('click', function(e) {
       e.preventDefault();
       activeElem = $('#featured_resources_area .resource_box.active');
       activeElemId = activeElem.data("id");
       activeElem.find('p.name span').text($("#resourcechosen_name").val());
       activeElem.find('input[name="featuredinsightname_'+activeElemId+'"]').val($("#resourcechosen_name").val());
       activeElem.find('input[name="featuredinsightid_'+activeElemId+'"]').val($("#resourcechosen_id").val());
       activeElem.find('p.color span').text($( "#resourcechosen_color option:selected" ).text());
       activeElem.find('input[name="featuredinsightcolor_'+activeElemId+'"]').val($("#resourcechosen_color").val());
       activeElem.find('p.image span').text($( "#resourcechosen_image option:selected" ).text());
       activeElem.find('input[name="featuredinsightimage_'+activeElemId+'"]').val($("#resourcechosen_image").val());
       $('#featured_resources_area .resource_box').removeClass("active");
       $(".resource_search_holder").slideUp("fast");
       activeElem = false;
       activeElemId = 0;
       $('html, body').animate({
           scrollTop: $("#featured_resources_area").offset().top
       }, 300);
     });
     $('.cancel_insight_edit').on('click', function(e) {
        $('#featured_resources_area .resource_box').removeClass("active");
        activeElem = false;
         activeElemId = 0;
        $(".resource_search_holder").slideUp("fast");
        $('html, body').animate({
             scrollTop: $("#featured_resources_area").offset().top
         }, 300);
       });
</script>