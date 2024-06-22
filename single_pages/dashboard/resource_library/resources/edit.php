<?php

defined("C5_EXECUTE") or die("Access Denied.");
$pageSelector = Loader::helper("form/page_selector");
$therapeuticAreas = Page::getByID(232)->getCollectionChildrenArray();
$resourceExperts =
  strlen($resource["resourceExperts"]) > 0
    ? explode(",", $resource["resourceExperts"])
    : [];
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
.checkbox {
    margin-right: 10px;
}

.form-group input[type="checkbox"] {
    display: inline-block;
    margin-right: 10px;
}

.sub_list {
    display: none;
    padding: 0px 25px;
}

.checkbox.topic:checked+label+br+.sub_list {
    display: block;
}

.btngroup {
    margin-top: 2.5em;
}

ul#insight_filters {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: row;
}

ul#insight_filters li {
    margin-right: 10px;
}

ul#insight_filters li input[type="text"],
ul#insight_filters li select {
    width: 250px;
    height: 30px;
}

#resources_list {
    border: 3px solid black;
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 250px;
    overflow-y: : scroll;
    overflow-x: hidden;
    margin-top: 25px;
    display: none;
}

#resources_list li {
    border-bottom: 1px solid black;
    padding: 5px;
    background-color: #ddd;
}

#resources_list li:hover,
#resources_list li:hover span {
    background-color: #ccc;
}

#resources_list li:last-child {
    border-bottom: 0;
}

#resources_list li a {
    display: block;
    background-color: #ddd;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    cursor: pointer;
}

#resources_list li a span.title {
    width: calc(100% - 100px);
    cursor: pointer;
    pointer-events: none;
}

#resources_list li a span.plus {
    text-align: right;
    width: 50px;
    cursor: pointer;
    font-size: 18px;
    pointer-events: none;
}

#resources_list li a span.plus:hover {
    color: blue;
}

#selected_resources_list {
    border: 3px solid black;
    list-style: none;
    margin: 0;
    padding: 0;
    margin-top: 25px;
}

#selected_resources_list li {
    border-bottom: 1px solid black;
    padding: 5px;
    background-color: #ddd;
    display: flex;
    flex-direction: row;
}

#selected_resources_list li:hover,
#selected_resources_list li:hover span {
    background-color: #ccc;
}

#selected_resources_list li:last-child {
    border-bottom: 0;
}

#selected_resources_list li a {
    width: 20px;
    cursor: pointer;
    font-size: 18px;
}

#selected_resources_list li a.remove {
    text-align: right;
    color: red;
}

#selected_resources_list li span {
    width: calc(100% - 40px);
}

#selected_resources_list li input[type="text"] {
    display: none;
}
</style>

<form method="post" enctype="multipart/form-data" action="<?php echo View::action(
    $actionName
); ?>">

    <p>Use the tabs to navigate through each step of creating a resource.</p>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Basic Details</a></li>
            <li><a href="#tabs-6">Experts</a></li>
            <li><a href="#tabs-2">Content</a></li>
            <li><a href="#tabs-3">Media</a></li>
            <li><a href="#tabs-7">Related</a></li>
            <li><a href="#tabs-4">Gated</a></li>
            <li><a href="#tabs-5">SEO</a></li>
        </ul>

        <div id="tabs-1">
            <div class="form-group">
                <?php echo $form->label("resourceName", t("Resource Name")); ?>
                <input type="text" class="form-control" name="resourceName" value="<?php if (
                  $id !== 0
                ) {
    echo $resource["resourceName"];
} ?>" <?php echo $readonly; ?>>
            </div>
            <div class="form-group">
                <input type="hidden" name="originalSlug" value="<?php if (
                  $id !== 0
                ) {
    echo $resource["resourceSlug"];
} ?>">
                <?php echo $form->label("resourceSlug", t("Resource Slug")); ?>
                <input type="text" class="form-control" name="resourceSlug" value="<?php if (
                  $id !== 0
                ) {
    echo $resource["resourceSlug"];
} ?>" <?php echo $readonly; ?>>
            </div>
            <div class="form-group">
                <?php echo $form->label(
    "resourceDescription",
    t("Resource Description")
); ?>
                <textarea class="form-control" name="resourceDescription" id="resourceDescription" <?php echo $readonly; ?>><?php if (
  $id !== 0
) {
                    echo $resource["resourceDescription"];
                } ?></textarea>
            </div>

            <div class="form-group" style="display: none;">
                <?php echo $form->label(
                    "resourceAuthor",
                    t("Resource Author")
                ); ?>
                <input type="text" class="form-control" name="resourceAuthor" value="<?php if (
                  $id !== 0
                ) {
                    echo $resource["resourceAuthor"];
                } ?>" <?php echo $readonly; ?>>
            </div>
            <?php if ($actionName !== "delete") { ?>
            <div class="form-group">
                <?php echo $form->label(
                    "resourcePageType",
                    t("What style of resource is this?")
                ); ?>
                <select class="form-control" name="resourcePageType">
                    <?php foreach ($resourcePageTypes as $key => $pageType) {
                    if (
                        isset($resource) &&
                        $resource["resourcePageType"] == $key + 1
                      ) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' .
                        ($key + 1) .
                        '" ' .
                        $selected .
                        ">" .
                        $pageType .
                        "</option>";
                } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($actionName !== "delete") { ?>
            <div class="form-group">
                <?php echo $form->label("resourceType", t("Resource Type")); ?>
                <select class="form-control" name="resourceType">
                    <option val="0">Select Resource Type</option>
                    <?php foreach ($resourceTypes as $type) {
                    if (
                        isset($resource) &&
                        $resource["resourceType"] == $type["id"]
                      ) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' .
                        $type["id"] .
                        '" ' .
                        $selected .
                        ">" .
                        $type["typeName"] .
                        "</option>";
                } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($actionName !== "delete") { ?>
            <div class="form-group">
                <?php echo $form->label(
                    "resourceTopics",
                    t("Resource Topics")
                ); ?>
                <br>
                <?php foreach ($resourceTopics as $key => $topic) {
                    $checked =
                    $resourceTopicsChosen &&
                    in_array($topic["id"], $resourceTopicsChosen)
                      ? "checked"
                      : null;
                    echo '<input type="checkbox" value="1" class="checkbox topic" id="topic' .
                    $key .
                    '" name="topic[' .
                    $topic["id"] .
                    ']" ' .
                    $checked .
                    '><label for="topic' .
                    $key .
                    '">' .
                    $topic["topicName"] .
                    "</label><br>";
                    echo '<div class="sub_list">';
                    foreach ($resourceSubTopics as $keySub => $subTopic) {
                        if (
                      $topic["id"] == $subTopic["topicId"] &&
                      strlen($subTopic["subTopicName"]) > 0
                    ) {
                            $checked =
                        $resourceSubTopicsChosen &&
                        in_array($subTopic["id"], $resourceSubTopicsChosen)
                          ? "checked"
                          : null;
                            echo '<input type="checkbox" value="1" class="checkbox" id="subtopic' .
                        $keySub .
                        '" name="subtopic[' .
                        $subTopic["id"] .
                        ']" ' .
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
            <div class="form-group">
                <?php echo $form->label(
                    "resourceAreas",
                    t("Therapeutic Areas")
                ); ?>
                <br>
                <?php foreach ($therapeuticAreas as $key => $area) {
                    $checked =
                    $resourceAreasChosen &&
                    in_array($area, $resourceAreasChosen)
                      ? "checked"
                      : null;
                    echo '<input type="checkbox" value="1" class="checkbox area" id="area' .
                    $key .
                    '" name="area[' .
                    $area .
                    ']" ' .
                    $checked .
                    '><label for="area' .
                    $key .
                    '">' .
                    Page::getByID($area)->getCollectionName() .
                    "</label><br>";
                } ?>
            </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->label(
                    "resourceActive",
                    t("Resource is Active")
                ); ?><br>
                <input type="checkbox" class="checkbox" value="1" name="resourceActive" id="resourceActive" checked> <label for="resourceActive">Check if Active</label>
            </div>
        </div>
        <div id="tabs-2">
            <div class="form-group resource-external" style="display: <?php echo isset(
              $resource
            ) && $resource["resourcePageType"] == 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label("resourceUrl", t("Resource Link")); ?>
                <input type="text" class="form-control" name="resourceUrl" value="<?php if (
                  $id !== 0
                ) {
                  echo $resource["resourceUrl"];
              } ?>" <?php echo $readonly; ?>>
            </div>
            <div class="form-group">
                <?php echo $form->label(
                  "resourceContent",
                  t("Resource Copy")
              ); ?>
                <?php
                $editor = Core::make("editor");
                echo $editor->outputStandardEditor(
                    "resourceContent",
                    !empty($resource) ? $resource["resourceContent"] : null
                );
                ?>
            </div>
            <div class="form-group resource-video" style="display: 
                                                <?php echo isset($resource) &&
                                                ($resource["resourceType"] ==
                                                  3 ||
                                                  $resource["resourceType"] ==
                                                    9)
                                                  ? "block"
                                                  : "none"; ?>
                                                ;">
                <?php echo $form->label(
                                                      "resourceMediaLink",
                                                      t("Enter link for YouTube or Vimeo Video")
                                                  ); ?>
                <input type="text" class="form-control" name="resourceMediaLink" value="<?php if (
                  $id !== 0
                ) {
                    echo $resource["resourceMediaLink"];
                } ?>" <?php echo $readonly; ?>>
            </div>
            <div class="form-group resource-video" style="display: 
                                                <?php echo isset($resource) &&
                                                ($resource["resourceType"] ==
                                                  3 ||
                                                  $resource["resourceType"] ==
                                                    9)
                                                  ? "block"
                                                  : "none"; ?>
                                                ;">
                <?php echo $form->label(
                  "resourceVideoTranscript",
                  t("Resource Video Transcription")
              ); ?>
                <?php
                $editor = Core::make("editor");
                echo $editor->outputStandardEditor(
                    "resourceVideoTranscript",
                    !empty($resource) ? $resource["resourceVideoTranscript"] : null
                );
                ?>
            </div>
            <div class="form-group resource-picker" style="display: 
                                                <?php echo isset($resource) &&
                                                ($resource["resourceType"] ==
                                                  1 ||
                                                  $resource["resourceType"] ==
                                                    2)
                                                  ? "block"
                                                  : "none"; ?>
                                                ;">
                <?php echo $form->label(
                                                      "resourceCid",
                                                      t("Select page to link to")
                                                  ); ?>
                <?php echo $pageSelector->selectPage(
                    "resourceCid",
                    $resource["resourceCid"]
                ); ?>
            </div>
        </div>
        <div id="tabs-6">
            <div class="form-group">
                <?php echo $form->label(
                    "resourceExpert1",
                    t("Select main expert")
                ); ?>
                <?php echo $pageSelector->selectPage(
                    "resourceExpert1",
                    count($resourceExperts) > 0 ? $resourceExperts[0] : false
                ); ?>
                <?php for ($x = 1; $x <= 10; $x++) {
                    echo $form->label(
                        "resourceExpert" . ($x + 1),
                        t("Select additional expert")
                    );
                    echo $pageSelector->selectPage(
                        "resourceExpert" . ($x + 1),
                        isset($resourceExperts[$x]) ? $resourceExperts[$x] : false
                    );
                } ?>
                <hr>
                <div class="form-group">
                    <p>You can manually enter a custom author's information below also.</p>
                    <?php echo $form->label(
                    "customAuthorByline1",
                    t("Custom Author 1 Byline")
                ); ?>
                    <input type="text" class="form-control" name="customAuthorByline1" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor1
                    ) {
                        echo $resourceCustomAuthor1["byline"];
                    } ?>" <?php echo $readonly; ?>>
                    <br>
                    <?php echo $form->label(
                        "customAuthorThumbnailPath1",
                        t("Custom Author 1 Thumbnail Path")
                    ); ?>
                    <input type="text" class="form-control" name="customAuthorThumbnailPath1" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor1
                    ) {
                        echo $resourceCustomAuthor1["thumb"];
                    } ?>" <?php echo $readonly; ?>>
                    <br>
                    <?php echo $form->label(
                        "customAuthorUrl1",
                        t("Custom Author 1 URL")
                    ); ?>
                    <input type="text" class="form-control" name="customAuthorUrl1" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor1
                    ) {
                        echo $resourceCustomAuthor1["url"];
                    } ?>" <?php echo $readonly; ?>>
                    <hr>
                    <?php echo $form->label(
                        "customAuthorByline2",
                        t("Custom Author 2 Byline")
                    ); ?>
                    <input type="text" class="form-control" name="customAuthorByline2" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor2
                    ) {
                        echo $resourceCustomAuthor2["byline"];
                    } ?>" <?php echo $readonly; ?>>
                    <br>
                    <?php echo $form->label(
                        "customAuthorThumbnailPath2",
                        t("Custom Author 2 Thumbnail Path")
                    ); ?>
                    <input type="text" class="form-control" name="customAuthorThumbnailPath2" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor2
                    ) {
                        echo $resourceCustomAuthor2["thumb"];
                    } ?>" <?php echo $readonly; ?>>
                    <br>
                    <?php echo $form->label(
                        "customAuthorUrl2",
                        t("Custom Author 2 URL")
                    ); ?>
                    <input type="text" class="form-control" name="customAuthorUrl2" value="<?php if (
                      $id !== 0 &&
                      $resourceCustomAuthor2
                    ) {
                        echo $resourceCustomAuthor2["url"];
                    } ?>" <?php echo $readonly; ?>>
                </div>
            </div>
        </div>
        <div id="tabs-3">
            <div class="form-group">
                <?php echo $form->label(
                        "resourceThumbnail",
                        t("Resource Thumbnail")
                    ); ?>
                <?php if ($id !== 0 && !empty($thumbnailAsset)) {
                    echo '<input type="hidden" name="originalThumbnail" value="' .
                    $thumbnailAsset["id"] .
                    '">';
                    echo '<p class="originalThumbnail"><a href="/application/files/resources/thumbnails/' .
                    $thumbnailAsset["fileName"] .
                    '"><img src="/application/files/resources/thumbnails/' .
                    $thumbnailAsset["fileName"] .
                    '" style="max-width: 250px; width: 100%; height: auto;"></a></p>';
                    echo '<p><a target="_blank" class="btn btn-primary removethumbnail">Remove Thumbnail</a></p>
                                ';
                } ?>
                <input type="file" name="resourceThumbnail" class="form-control" id="resourceThumbnail" />
            </div>
            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label("fileName", t("Resource Assets")); ?>
                <?php if ($id !== 0 && !empty($resourceAsset)) {
                  echo '<input type="hidden" name="originalAsset" value="' .
                    $resourceAsset["id"] .
                    '">';
                  echo '<p class="originalAsset"><a href="/application/files/resources/assets/' .
                    $resourceAsset["fileName"] .
                    '" target="_blank">Existing Resource: ' .
                    $resourceAsset["fileName"] .
                    "</a></p>";
                  echo '<p><a target="_blank" class="btn btn-primary removeasset">Remove Asset</a></p>
                                ';
              } ?>
                <input type="file" name="fileName" class="form-control" id="fileName" />
            </div>
        </div>
        <div id="tabs-7">
            <div class="form-group">
                <p>Related resources will automatically populate on the page based on this insight's Topics and Areas chosen in the first tab. If you'd like you may also override the automatically placed insights and choose your own below.</p>
                <p>Use the search below to find the resource you are looking for. You may then add it to the list below. Below you may add, edit, delete, and re-order the feature resources. You may select up to 12 items.</p>

                <ul id="insight_filters">
                    <li>
                        <input lass="form-control" type="text" placeholder="What are you looking for?">
                        <button class="inputSearch">Search</button>
                    </li>
                    <li class="select">
                        <select lass="form-control" name="typeForm" id="type">
                            <option value="0">Content Type</option>
                            <?php foreach ($resourceTypes as $type) {
                  echo '<option value="' .
                                $type["id"] .
                                '">' .
                                $type["typeName"] .
                                "</option>";
              } ?>
                        </select>
                    </li>
                    <li class="select">
                        <select lass="form-control" name="topicForm" id="topic">
                            <option value="0">Topic</option>
                            <?php foreach ($resourceTopics as $topic) {
                  echo '<option value="' .
                                $topic["id"] .
                                '">' .
                                $topic["topicName"] .
                                "</option>";
              } ?>
                        </select>
                    </li>
                </ul>

                <ul id="resources_list">

                </ul>
                <ul id="selected_resources_list">
                    <?php
                    $itemArray = [];
                    if (isset($resource)) {
                        $customRelated = $resource["resourceCustomRelated"];
                        $customRelatedIds = explode(",", $customRelated);
                        foreach ($customRelatedIds as $key => $customRelatedId) {
                            array_push($itemArray, $key + 1);
                            $thisResource = $this->controller->retrieveResource(
                                $customRelatedId
                            );
                            if ($thisResource) { ?>
                    <li data-id="<?php echo $key + 1; ?>">
                        <a class="move">&varr;</a>
                        <span><?php echo $thisResource[
                          "resourceName"
                        ]; ?></span>
                        <input type="text" class="related_input" name="related_<?php echo $key +
                          1; ?>" id="related_<?php echo $key +
  1; ?>" value="<?php echo $customRelatedId; ?>">
                        <a class="remove">X</a>
                    </li>
                    <?php }
                        }
                    }
                    ?>
                </ul>
                <input type="hidden" name="relatedOrder" id="relatedOrder" value="<?php echo implode(
                        ",",
                        $itemArray
                    ); ?>">
            </div>
        </div>
        <div id="tabs-4">
            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGated",
                  t("Resource Asset is Gated")
              ); ?><br>
                <?php $isResourceGated =
                  !empty($resource) &&
                  array_key_exists("resourceGated", $resource) &&
                  $resource["resourceGated"] == 1
                    ? "checked"
                    : ""; ?>
                <input type="checkbox" class="checkbox" value="1" name="resourceGated" id="resourceGated" <?php echo $isResourceGated; ?>> <label for="resourceGated">Check if Resource Asset is Gated</label>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormTitle",
                  t("Gated Content Form Custom Title")
              ); ?><br>
                <input type="text" class="form-control" name="resourceGatedFormTitle" value="<?php if (
                  $id !== 0 &&
                  !empty($resource) &&
                  array_key_exists("resourceGatedFormTitle", $resource)
                ) {
                    echo $resource["resourceGatedFormTitle"];
                } ?>" <?php echo $readonly; ?>>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormCopy",
                  t("Gated Content Form Custom Copy")
              ); ?><br>
                <?php
                $editor = Core::make("editor");
                echo $editor->outputStandardEditor(
                    "resourceGatedFormCopy",
                    !empty($resource) &&
                  array_key_exists("resourceGatedFormCopy", $resource)
                    ? $resource["resourceGatedFormCopy"]
                    : null
                );
                ?>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormCtaTitle",
                  t("Gated Content Form Custom CTA Title")
              ); ?><br>
                <input type="text" class="form-control" name="resourceGatedFormCtaTitle" value="<?php if (
                  $id !== 0 &&
                  !empty($resource) &&
                  array_key_exists("resourceGatedFormCtaTitle", $resource)
                ) {
                    echo $resource["resourceGatedFormCtaTitle"];
                } ?>" <?php echo $readonly; ?>>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormImage",
                  t("Select Optional Gated Content Form Image")
              ); ?>
                <?php
                $service = Core::make("helper/concrete/file_manager");
                print $service->file(
                    "resourceGatedFormImage",
                    "resourceGatedFormImage",
                    "Select Optional Gated Content Form Image",
                    isset($resource["resourceGatedFormImage"])
                    ? $resource["resourceGatedFormImage"]
                    : null
                );
                ?>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormId",
                  t(
                      "Gated Form Unique Form Handler Path (Please enter full form handler path)"
                  )
              ); ?><br>
                <input type="text" class="form-control" name="resourceGatedFormId" value="<?php if (
                  $id !== 0 &&
                  !empty($resource) &&
                  array_key_exists("resourceGatedFormId", $resource)
                ) {
                    echo $resource["resourceGatedFormId"];
                } ?>" <?php echo $readonly; ?>>
            </div>

            <div class="form-group resource-not-external" style="display: <?php echo !isset(
              $resource
            ) || $resource["resourcePageType"] !== 2
              ? "block"
              : "none"; ?>;">
                <?php echo $form->label(
                  "resourceGatedFormPardotName",
                  t(
                      "Gated Form Pardot Form Name (used for GDPR Consent Source)"
                  )
              ); ?><br>
                <input type="text" class="form-control" name="resourceGatedFormPardotName" value="<?php if (
                  $id !== 0 &&
                  !empty($resource) &&
                  array_key_exists("resourceGatedFormPardotName", $resource)
                ) {
                    echo $resource["resourceGatedFormPardotName"];
                } ?>" <?php echo $readonly; ?>>
            </div>

            <div class="form-group" style="display:none;">
                <?php echo $form->label(
                    "resourceGatedContent",
                    t("Additional Gated Content")
                ); ?><br>
                <?php
                $editor = Core::make("editor");
                echo $editor->outputStandardEditor(
                    "resourceGatedContent",
                    !empty($resource) &&
                  array_key_exists("resourceGatedContent", $resource)
                    ? $resource["resourceGatedContent"]
                    : null
                );
                ?>
            </div>
        </div>
        <div id="tabs-5">
            <div class="form-group">
                <?php echo $form->label("resourceSeoTitle", t("SEO Title")); ?>
                <input type="text" class="form-control" name="resourceSeoTitle" value="<?php if (
                  $id !== 0
                ) {
                    echo $resource["resourceSeoTitle"];
                } ?>" <?php echo $readonly; ?>>
            </div>
            <div class="form-group">
                <?php echo $form->label(
                    "resourceSeoDescription",
                    t("SEO Description")
                ); ?>
                <textarea class="form-control" name="resourceSeoDescription" id="resourceSeoDescription" <?php echo $readonly; ?>><?php if (
  $id !== 0
) {
                    echo $resource["resourceSeoDescription"];
                } ?></textarea>
            </div>
            <div class="form-group">
                <?php echo $form->label(
                    "resourceCanonicalUrl",
                    t("Resource Canonical URL Override")
                ); ?>
                <input type="text" class="form-control" name="resourceCanonicalUrl" value="<?php if (
                  $id !== 0
                ) {
                    if (isset($resource["resourceCanonicalUrl"])) {
                        echo $resource["resourceCanonicalUrl"];
                    }
                } ?>" <?php echo $readonly; ?>>
            </div>
        </div>
    </div>
    <div class="form-group btngroup">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
        <input type="hidden" name="topics" value="">
        <input type="hidden" name="subtopics" value="">
        <a href="/index.php/dashboard/resource_library/resources/" class="btn <?php echo $btnClasses[0]; ?>">Cancel</a>
        <input type="submit" data-dialog-action="submit" class="btn <?php echo $btnClasses[1]; ?>" value="<?php echo $btnText; ?>">
    </div>
</form>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(".removeasset").click(function() {
    $("p.originalAsset").remove();
    $(this).remove();
    $('input[name="originalAsset"]').val(0);
});
$(".removethumbnail").click(function() {
    $("p.originalThumbnail").remove();
    $(this).remove();
    $('input[name="originalThumbnail"]').val(0);
});
$("select[name='resourcePageType']").change(function(e) {
    e.preventDefault();
    if ($(this).val() == 2) {
        $(".resource-external").show();
        $(".resource-video").hide();
        $(".resource-not-external").hide();
    } else if ($(this).val() == 3) {
        $(".resource-external").hide();
        $(".resource-video").show();
        $(".resource-not-external").hide();
    } else {
        $(".resource-external").hide();
        $(".resource-video").hide();
        $(".resource-not-external").show();
    }
});
$("select[name='resourceType']").change(function(e) {
    e.preventDefault();
    if ($(this).val() == 1) {
        $(".resource-video").hide();
        $(".resource-picker").show();
    } else if ($(this).val() == 2) {
        $(".resource-video").hide();
        $(".resource-picker").show();
    } else if ($(this).val() == 3 || $(this).val() == 9) {
        $(".resource-video").show();
        $(".resource-picker").hide();
    } else {
        $(".resource-video").hide();
        $(".resource-picker").hide();
    }
});
$("#tabs").tabs();
</script>
<?php if ($id == 0) { ?>
<script>
$("input[name='resourceName']").keyup(function() {
    val = $(this).val();
    slugVal = val.toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    $("input[name='resourceSlug']").val(slugVal);
});
</script>
<?php } ?>
<script>
featuredItems = [];

function performFilter() {
    searchQuery = $("#insight_filters input[type='text']").val();
    contentTypeSearch = $("#insight_filters select#type").val();
    topicSearch = $("#insight_filters select#topic").val();
    resourceList = $("#resources_list");
    resourceOptions = $("#featured_resource_options");
    if (searchQuery == "" && contentTypeSearch == 0 && topicSearch == 0) {
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

function showNewResources(data) {
    // console.log(data);
    resourceList.show();
    resourceList.html("");
    $('html, body').animate({
        scrollTop: $("#resources_list").offset().top
    }, 300);
    // $("#resources_list .resource").removeClass("active");
    data.forEach(data => {
        resourceList.append("<li><a class='additem'><span class='title' data-id='" + data.id + "'>" + data.name + "</span><span class='plus'>+</span></a></li>");
        Object.entries(data).forEach(([key, value]) => {
            // resourceList.append("<li><span></span></li>");
            if (key == "id") {
                // $("#resources_list a.resource[data-id='"+value+"']").addClass("active");
            }
        });
    });
}
$(".inputSearch").click(function(e) {
    e.preventDefault();
    performFilter();
});
$('#insight_filters select').on('change', function() {
    performFilter();
});
$("body").on("click", "#selected_resources_list a.move", function(e) {
    e.preventDefault();
});
$("body").on("click", "#selected_resources_list a.remove", function(e) {
    e.preventDefault();
    $(this).parent("li").remove();
    getNewMoveOrder();
});
$("body").on("click", "#resources_list a.additem", function(e) {
    e.preventDefault();
    if ($("#selected_resources_list li").length >= 12) {
        alert("You have already added the maximum number of items. Please remove other items to add new ones.");
    } else {
        $("#selected_resources_list").append('<li data-id="' + ($("#selected_resources_list li").length + 1) + '" style="" class=""><a class="move ui-sortable-handle">↕</a><span>' + $(this).children("span.title").text() + '</span><input type="text" class="related_input" name="related_' + ($("#selected_resources_list li").length + 1) + '" id="related_' + ($("#selected_resources_list li").length + 1) + '" value="' + $(this).children("span.title").data("id") + '"><a class="remove">X</a></li>');
        getNewMoveOrder();
    }
});
$("#selected_resources_list").sortable({
    handle: '.move',
    update: function(event, ui) {
        getNewMoveOrder();
    },
});

function getNewMoveOrder() {
    moveOrder = [];
    $("#selected_resources_list li").each(function(i) {
        moveOrder.push($(this).data("id"));
    });
    $("#relatedOrder").val(moveOrder.toString());
}
</script>