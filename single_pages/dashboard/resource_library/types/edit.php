<?php

defined("C5_EXECUTE") or die("Access Denied."); 

if(intval($resource['typeDefault']) == 1){ 
    $readonly = "readonly";   
}

?>


<form method="post" action="<?php echo View::action($actionName); ?>">
    <div class="form-group">
        <?php echo $form->label("typeName", t("Resource Type Name")); ?>
        <input type="text" class="form-control" name="typeName" value="<?php if (
          $id !== 0
        ) {
    echo $resource["typeName"];
} ?>" <?php echo $readonly; ?>>
    </div>
    <input type="hidden" name="id" id="id" value="<?php echo (intval($resource['typeDefault']) == 1) ? null : $id; ?>">
    <?php if(intval($resource['typeDefault']) !== 1){ ?>
    <div class="form-group">
        <a href="/index.php/dashboard/resource-library/types/" class="btn <?php echo $btnClasses[0]; ?>">Cancel</a>
        <input type="submit" data-dialog-action="submit" class="btn <?php echo $btnClasses[1]; ?>" value="<?php echo $btnText; ?>">
    </div>
    <?php } ?>
</form>