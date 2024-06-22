<style>
.checkbox {
    margin-right: 10px;
}

.sub_list {
    display: none;
    padding: 0px 25px;
}

.checkbox.topic:checked+label+br+.sub_list {
    display: block;
}
</style>

<div class="form-group">
    <?php foreach ($resourceTopics as $key => $topic) {
    $checked =
        $finalVal && in_array("topic_" . $topic["id"], $finalVal)
          ? "checked"
          : "";
    echo '<input type="checkbox" value="topic_' .
        $topic["id"] .
        '" class="checkbox topic" id="topic' .
        $key .
        '" name="' .
        $view->field("value") .
        '[]" ' .
        $checked .
        '><label for="topic' .
        $key .
        '">' .
        $topic["topicName"] .
        "</label><br>";
    echo '<div class="sub_list">';
    foreach ($resourceSubTopics as $keySub => $subTopic) {
        if ($subTopic["topicId"] == $topic["id"]) {
            $checked =
            $finalVal && in_array("subtopic_" . $subTopic["id"], $finalVal)
              ? "checked"
              : "";
            echo '<input type="checkbox" value="subtopic_' .
            $subTopic["id"] .
            '" class="checkbox" id="subtopic' .
            $keySub .
            '" name="' .
            $view->field("value") .
            '[]" ' .
            $checked .
            '><label for="subtopic' .
            $keySub .
            '">' .
            $subTopic["subTopicName"] .
            "</label><br>";
        }
    }
    echo "</div>";
} ?>
</div>