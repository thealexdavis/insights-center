<?php

defined("C5_EXECUTE") or die("Access Denied."); ?>

<style>
.subtopic_list .subtopic {
    margin-bottom: 15px;
}
</style>

<form method="post" action="<?php echo View::action($actionName); ?>">
    <div class="form-group">
        <?php echo $form->label("topicName", t("Resource Topic Name")); ?>
        <input type="text" class="form-control" name="topicName" value="<?php if (
          $id !== 0
        ) {
    echo $resource["topicName"];
} ?>" <?php echo $readonly; ?>>
    </div>
    <div class="form-group">
        <?php echo $form->label(
    "subtopics",
    t("Enter subtopics for this topic")
); ?>
        <div class="subtopic_list">
            <?php if (!empty($subtopics)) {
            foreach ($subtopics as $key => $subtopic) { ?>
            <input type="hidden" name="topicSubtopic[<?php echo $key; ?>][id]" value="<?php echo $subtopic[
  "id"
]; ?>">
            <input type="text" class="subtopic form-control" name="topicSubtopic[<?php echo $key; ?>][name]" value="<?php echo $subtopic[
  "subTopicName"
]; ?>" <?php echo $readonly; ?>>
            <?php }
        } else {
            ?>
            <input type="hidden" name="topicSubtopic[0][id]" value="0">
            <input type="text" class="subtopic form-control" name="topicSubtopic[0][name]" value="" <?php echo $readonly; ?>>
            <?php
        } ?>
        </div>
        <a class="btn btn-primary add_subtopic">Add New Subtopic</a>
    </div>
    <div class="form-group">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <a href="/index.php/dashboard/resource_library/topics" class="btn <?php echo $btnClasses[0]; ?>">Cancel</a>
        <input type="submit" data-dialog-action="submit" class="btn <?php echo $btnClasses[1]; ?>" value="<?php echo $btnText; ?>">
    </div>
</form>

<script>
subTopicCount = $("input.subtopic[type='text']").length;
$(".add_subtopic").click(function(e) {
    e.preventDefault();
    $(".subtopic_list").append('<input type="hidden" name="topicSubtopic[' + subTopicCount + '][id]" value="0"><input type="text" class="subtopic form-control" name="topicSubtopic[' + subTopicCount + '][name]" value="">');
    subTopicCount++;
});
</script>