<?php

defined("C5_EXECUTE") or die("Access Denied.");

$actual_link_full =
  (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") .
  "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link_parts = explode("&page=", $actual_link_full);
$actual_link = $actual_link_parts[0];
if (parse_url($actual_link, PHP_URL_QUERY)) {
    $pageChar = "&";
} else {
    $pageChar = "?";
}
if (!isset($_GET["num"])) {
    $defaultChoice = 50;
} else {
    $defaultChoice = $_GET["num"];
}

echo '<div class="btn_row" style="margin-bottom: 25px;"><a href="/index.php/dashboard/resource_library/resources/add" style="margin-right: 25px;" class="btn btn-primary">ADD RESOURCE</a><a href="/index.php/dashboard/resource_library/resources/featured" class="btn btn-primary">EDIT FEATURED RESOURCES</a></div>';

if (empty($resources)) {
    echo "<h3>There are no resources at this time.</h3>";
} else {
    ?>

<style>
form.filters {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

form.filters .form-group {
    width: 48%;
    margin-right: 4%;
}

form.filters .form-group:nth-child(even) {
    margin-right: 0;
}

form.filters .form-group.full {
    width: 100%;
    margin-right: 0;
}
</style>

<form method="get" class="filters" action="<?php echo $actual_link; ?>">
    <div class="form-group">
        <?php echo $form->label("num", t("Number of Results")); ?>
        <select class="form-control" name="num">
            <?php foreach ($resultNumChoices as $numChoice) {
        $selected = $numChoice == $defaultChoice ? "selected" : false;
        echo '<option value="' .
                $numChoice .
                '" ' .
                $selected .
                ">" .
                $numChoice .
                "</option>";
    } ?>
        </select>
    </div>
    <div class="form-group">
        <?php echo $form->label("q", t("Search for Resource")); ?>
        <input type="text" class="form-control" name="q" value="<?php if (
          array_key_exists("q", $_GET)
        ) {
        echo $_GET["q"];
    } ?>">
    </div>
    <div class="form-group">
        <select class="form-control" lass="form-control" name="type" id="type">
            <option value="0">Content Type</option>
            <?php foreach ($resourceTypes as $type) {
        echo '<option value="' .
                $type["id"] .
                '">' .
                $type["typeName"] .
                "</option>";
    } ?>
        </select>
    </div>
    <div class="form-group full">
        <input type="hidden" name="page" value="1">
        <input type="submit" class="btn btn-primary" value="Apply Filters">
    </div>
</form>

<div id="ccm-search-results-table">
    <table class="ccm-search-results-table" data-search-results="resources">
        <thead>
            <tr>
                <?php foreach ($columns as $column) { ?>
                <th data-q="<?php echo $column["dbTitle"]; ?>">
                    <?php echo $column["columnTitle"]; ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resources as $resource) {
        echo "<tr>";
        foreach ($columns as $column) {
            if ($column["dbTitle"] !== "actions") {
                echo "<td class='ccm-search-results-name'>";
                if ($column["dbTitle"] == "resourceType") {
                    $keyVal = array_search(
                        $resource[$column["dbTitle"]],
                        array_column($resourceTypes, "id")
                    );
                    echo $resourceTypes[$keyVal]["typeName"];
                    $resource["typeName"] = $resourceTypes[$keyVal]["typeName"];
                } elseif ($column["dbTitle"] == "resourceTopics") {
                    $keyVal = array_search(
                        $resource[$column["dbTitle"]],
                        array_column($resourceTopics, "id")
                    );
                    echo $resourceTopics[$keyVal]["topicName"];
                } elseif ($column["dbTitle"] == "subtopics") {
                    echo $this->controller->returnSubTopics($resource["id"]);
                } elseif ($column["dbTitle"] == "resourceActive") {
                    echo $resource[$column["dbTitle"]] == 1
                      ? "Active"
                      : "Inactive";
                } else {
                    echo $resource[$column["dbTitle"]];
                }
            }
            if ($column["dbTitle"] == "actions") {
                echo "<td>";
                $resourcePage = $resource["resourceCid"]
                    ? Page::getByID($resource["resourceCid"])
                    : false;
                $delimiter = "-";
                $str = $resource["typeName"];
                $linkTarget = "_blank";
                $slugType = strtolower(
                    trim(
                        preg_replace(
                          "/[\s-]+/",
                          $delimiter,
                          preg_replace(
                            "/[^A-Za-z0-9-]+/",
                            $delimiter,
                            preg_replace(
                              "/[&]/",
                              "and",
                              preg_replace(
                                '/[\']/',
                                "",
                                iconv("UTF-8", "ASCII//TRANSLIT", $str)
                            )
                          )
                        )
                      ),
                        $delimiter
                    )
                );
                $resourceUrl = $resourcePage
                    ? $resourcePage->getCollectionPath()
                    : "/insights/" .
                      $slugType .
                      "/" .
                      $resource["resourceSlug"];
                echo '<a href="' .
                    $resourceUrl .
                    '" target="' .
                    $linkTarget .
                    '" class="btn btn-primary">View</a>&nbsp;<a href="/index.php/dashboard/resource_library/resources/edit/' .
                    $resource["id"] .
                    '" class="btn btn-primary">Edit</a>&nbsp;<a href="/index.php/dashboard/resource_library/resources/delete/' .
                    $resource["id"] .
                    '" class="btn btn-danger">Delete</a>';
            }
            echo "</td>";
        }
        echo "</tr>";
    } ?>
        </tbody>
    </table>
</div>
<div class="ccm-search-results-pagination">
    <div class="ccm-pagination-wrapper">
        <ul class="pagination">
            <?php if ($currentPage == 1) {
        echo '<li class="page-item prev disabled"><span class="page-link">← Previous</span></li>';
    } else {
        echo '<li class="page-item prev"><a href="' .
                $actual_link .
                "" .
                $pageChar .
                "page=" .
                ($currentPage - 1) .
                '"><span class="page-link">← Previous</span></a></li>';
    } ?>
            <?php foreach ($paginationRange as $pageNumber) {
        if ($pageNumber == $currentPage) {
            echo '<li class="page-item active"><span class="page-link">' .
                  $pageNumber .
                  ' <span class="sr-only">(current)</span></span></li>';
        } else {
            echo '<li class="page-item"><a href="' .
                  $actual_link .
                  "" .
                  $pageChar .
                  "page=" .
                  $pageNumber .
                  '"><span class="page-link">' .
                  $pageNumber .
                  "</span></a></li>";
        }
    } ?>
            <!-- <li class="page-item active"><span class="page-link">1 <span class="sr-only">(current)</span></span></li> -->
            <?php if ($currentPage == $maxPage) {
        echo '<li class="page-item next disabled"><span class="page-link">Next →</span></li>';
    } else {
        echo '<li class="page-item next"><a href="' .
                $actual_link .
                "" .
                $pageChar .
                "page=" .
                ($currentPage + 1) .
                '"><span class="page-link">Next →</span></a></li>';
    } ?>
        </ul>
    </div>
</div>
<?php
}
