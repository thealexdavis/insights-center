<?php

defined("C5_EXECUTE") or die("Access Denied."); ?>


<form method="post" enctype="multipart/form-data" action="<?php echo View::action(
    $actionName
); ?>">
    <div class="form-group">
        <?php echo $form->label("topicName", t("Resource Assets")); ?>
        <input type="file" name="fileName" class="form-control" id="fileName" />
    </div>
    <div class="form-group">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <a href="/index.php/dashboard/resource-library/assets/" class="btn <?php echo $btnClasses[0]; ?>">Cancel</a>
        <input type="submit" data-dialog-action="submit" class="btn <?php echo $btnClasses[1]; ?>" value="<?php echo $btnText; ?>">
    </div>
</form>