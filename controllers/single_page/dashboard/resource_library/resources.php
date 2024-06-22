<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage\Dashboard\ResourceLibrary;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;
use Concrete\Package\PropertyTaxRates\EntityFactory;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Url;

defined("C5_EXECUTE") or die("Access Denied.");

class Resources extends DashboardPageController
{
    /**
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function save(): RedirectResponse
    {
        if ($this->validate($_POST)) {
            ini_set("upload_max_filesize", "25M");
            ini_set("post_max_size", "25M");
            ini_set("max_input_time", 300);
            ini_set("max_execution_time", 300);
            $db = \Database::connection();

            for ($z = 1; $z <= 2; $z++) {
                $output = [
          "byline" => isset($_POST["customAuthorByline" . $z])
            ? $_POST["customAuthorByline" . $z]
            : false,
          "thumb" => isset($_POST["customAuthorThumbnailPath" . $z])
            ? $_POST["customAuthorThumbnailPath" . $z]
            : false,
          "url" => isset($_POST["customAuthorUrl" . $z])
            ? $_POST["customAuthorUrl" . $z]
            : false,
        ];
                ${"customAuthor" . $z} = serialize($output);
            }

            if ($_POST["id"] == 0) {
                $newAsset = 0;
                if ($_FILES["fileName"]["name"]) {
                    $newAsset = $this->createNewAsset($_FILES);
                }
                if ($_FILES["resourceThumbnail"]["name"]) {
                    $newThumbnail = $this->createNewThumbnail($_FILES);
                }
                $topicsChosen = [];
                $subtopicsChosen = [];
                $areasChosen = [];
                if (array_key_exists("topic", $_POST)) {
                    foreach ($_POST["topic"] as $key => $topic) {
                        if ($topic == 1) {
                            array_push($topicsChosen, $key);
                        }
                    }
                }
                if (array_key_exists("subtopic", $_POST)) {
                    foreach ($_POST["subtopic"] as $key => $subtopic) {
                        if ($subtopic == 1) {
                            array_push($subtopicsChosen, $key);
                        }
                    }
                }
                if (array_key_exists("area", $_POST)) {
                    foreach ($_POST["area"] as $key => $area) {
                        if ($area == 1) {
                            array_push($areasChosen, $key);
                        }
                    }
                }
                $slugCount = $this->doesSlugExist($_POST["resourceSlug"]);
                if ($slugCount > 0) {
                    $useSlug = $_POST["resourceSlug"] . "-" . $slugCount;
                } else {
                    $useSlug = $_POST["resourceSlug"];
                }
                $expertsIds = [];
                for ($x = 1; $x <= 11; $x++) {
                    if (
            isset($_POST["resourceExpert" . $x]) &&
            $_POST["resourceExpert" . $x] > 0
          ) {
                        array_push($expertsIds, $_POST["resourceExpert" . $x]);
                    }
                }
                $ctaBtn = isset($_POST["resourceCtaButton"])
          ? $_POST["resourceCtaButton"]
          : false;
                if (
          isset($_POST["relatedOrder"]) &&
          strlen($_POST["relatedOrder"]) > 0
        ) {
                    $relatedOrderArray = explode(",", $_POST["relatedOrder"]);
                    $relatedOrderPageObj = [];
                    foreach ($relatedOrderArray as $relatedOrderItem) {
                        if (
              isset($relatedOrderItem) &&
              $relatedOrderItem &&
              $relatedOrderItem > 0
            ) {
                            if (isset($_POST["related_" . $relatedOrderItem])) {
                                array_push(
                                    $relatedOrderPageObj,
                                    $_POST["related_" . $relatedOrderItem]
                                );
                            }
                        }
                    }
                    $customRelated = implode(",", $relatedOrderPageObj);
                } else {
                    $customRelated = 0;
                }
                if ($_POST["resourcePageType"] == 2 && isset($_POST["resourceUrl"])) {
                    $resourceUrl = $_POST["resourceUrl"];
                } else {
                    $resourceUrl = "";
                }
                $v = [
          $_POST["resourceName"],
          $_POST["resourceType"],
          $newAsset,
          $newThumbnail,
          implode(",", $topicsChosen),
          implode(",", $subtopicsChosen),
          implode(",", $areasChosen),
          implode(",", $expertsIds),
          $_POST["resourceContent"],
          $_POST["resourceGatedContent"],
          $_POST["resourceGatedFormTitle"],
          $_POST["resourceGatedFormCopy"],
          $_POST["resourceGatedFormCtaTitle"],
          $_POST["resourceGatedFormImage"],
          $_POST["resourceGatedFormId"],
          $_POST["resourceGatedFormPardotName"],
          isset($_POST["resourceActive"]) ? 1 : 0,
          isset($_POST["resourceGated"]) ? 1 : 0,
          $_POST["resourcePageType"],
          $resourceUrl,
          $useSlug,
          date("Y-m-d H:i:s"),
          $_POST["resourceAuthor"],
          $_POST["resourceMediaLink"],
          $_POST["resourceVideoTranscript"],
          $_POST["resourceCid"],
          $_POST["resourceSeoTitle"],
          $_POST["resourceSeoDescription"],
          $ctaBtn,
          $_POST["resourceDescription"],
          isset($_POST["resourceIsFeatured"]) ? 1 : 0,
          $_POST["resourceCanonicalUrl"],
          $customAuthor1,
          $customAuthor2,
          $customRelated,
        ];
                $q =
          "insert into resourcelibrary_resource ( resourceName,resourceType,resourceAsset,resourceThumbnail,resourceTopics,resourceSubTopics,resourceAreas,resourceExperts,resourceContent,resourceGatedContent,resourceGatedFormTitle,resourceGatedFormCopy,resourceGatedFormCtaTitle,resourceGatedFormImage,resourceGatedFormId,resourceGatedFormPardotName,resourceActive,resourceGated,resourcePageType,resourceUrl, resourceSlug, resourceCreated, resourceAuthor, resourceMediaLink, resourceVideoTranscript, resourceCid, resourceSeoTitle, resourceSeoDescription, resourceCtaButton, resourceDescription, resourceIsFeatured, resourceCanonicalUrl, resourceCustomAuthor1, resourceCustomAuthor2,resourceCustomRelated ) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $errorUrl = "/index.php/dashboard/resource_library/resources/add";
            } else {
                $newAsset = array_key_exists("originalAsset", $_POST)
          ? $_POST["originalAsset"]
          : 0;
                if ($_FILES["fileName"]["name"]) {
                    $newAsset = $this->createNewAsset($_FILES);
                }
                $newThumbnail = array_key_exists("originalThumbnail", $_POST)
          ? $_POST["originalThumbnail"]
          : 0;
                if ($_FILES["resourceThumbnail"]["name"]) {
                    $newThumbnail = $this->createNewThumbnail($_FILES);
                }
                $topicsChosen = [];
                $subtopicsChosen = [];
                $areasChosen = [];
                if (array_key_exists("topic", $_POST)) {
                    foreach ($_POST["topic"] as $key => $topic) {
                        if ($topic == 1) {
                            array_push($topicsChosen, $key);
                        }
                    }
                }
                if (array_key_exists("subtopic", $_POST)) {
                    foreach ($_POST["subtopic"] as $key => $subtopic) {
                        if ($subtopic == 1) {
                            array_push($subtopicsChosen, $key);
                        }
                    }
                }
                if (array_key_exists("area", $_POST)) {
                    foreach ($_POST["area"] as $key => $area) {
                        if ($area == 1) {
                            array_push($areasChosen, $key);
                        }
                    }
                }
                $expertsIds = [];
                for ($x = 1; $x <= 11; $x++) {
                    if (
            isset($_POST["resourceExpert" . $x]) &&
            $_POST["resourceExpert" . $x] > 0
          ) {
                        array_push($expertsIds, $_POST["resourceExpert" . $x]);
                    }
                }
                $slugCount = $this->doesSlugExist($_POST["resourceSlug"]);
                if (
          $slugCount > 0 &&
          $_POST["originalSlug"] !== $_POST["resourceSlug"]
        ) {
                    $useSlug = $_POST["resourceSlug"] . "-" . $slugCount;
                } else {
                    $useSlug = $_POST["resourceSlug"];
                }
                $ctaBtn = isset($_POST["resourceCtaButton"])
          ? $_POST["resourceCtaButton"]
          : false;
                if (
          isset($_POST["relatedOrder"]) &&
          strlen($_POST["relatedOrder"]) > 0
        ) {
                    $relatedOrderArray = explode(",", $_POST["relatedOrder"]);
                    $relatedOrderPageObj = [];
                    foreach ($relatedOrderArray as $relatedOrderItem) {
                        if (
              isset($relatedOrderItem) &&
              $relatedOrderItem &&
              $relatedOrderItem > 0
            ) {
                            if (isset($_POST["related_" . $relatedOrderItem])) {
                                array_push(
                                    $relatedOrderPageObj,
                                    $_POST["related_" . $relatedOrderItem]
                                );
                            }
                        }
                    }
                    $customRelated = implode(",", $relatedOrderPageObj);
                } else {
                    $customRelated = 0;
                }
                if ($_POST["resourcePageType"] == 2 && isset($_POST["resourceUrl"])) {
                    $resourceUrl = $_POST["resourceUrl"];
                } else {
                    $resourceUrl = "";
                }
                $v = [
          $_POST["resourceName"],
          $_POST["resourceType"],
          $newAsset,
          $newThumbnail,
          $_POST["resourceContent"],
          $_POST["resourceGatedContent"],
          $_POST["resourceGatedFormTitle"],
          $_POST["resourceGatedFormCopy"],
          $_POST["resourceGatedFormCtaTitle"],
          $_POST["resourceGatedFormImage"],
          $_POST["resourceGatedFormId"],
          $_POST["resourceGatedFormPardotName"],
          isset($_POST["resourceActive"]) ? 1 : 0,
          isset($_POST["resourceGated"]) ? 1 : 0,
          implode(",", $topicsChosen),
          implode(",", $subtopicsChosen),
          implode(",", $areasChosen),
          implode(",", $expertsIds),
          $_POST["resourcePageType"],
          $resourceUrl,
          $useSlug,
          date("Y-m-d H:i:s"),
          $_POST["resourceAuthor"],
          $_POST["resourceMediaLink"],
          $_POST["resourceVideoTranscript"],
          $_POST["resourceCid"],
          $_POST["resourceSeoTitle"],
          $_POST["resourceSeoDescription"],
          $ctaBtn,
          $_POST["resourceDescription"],
          isset($_POST["resourceIsFeatured"]) ? 1 : 0,
          $_POST["resourceCanonicalUrl"],
          $customAuthor1,
          $customAuthor2,
          $customRelated,
          $_POST["id"],
        ];
                $q =
          "update resourcelibrary_resource set resourceName = ?, resourceType = ?, resourceAsset = ?, resourceThumbnail = ?, resourceContent = ?, resourceGatedContent = ?, resourceGatedFormTitle = ?, resourceGatedFormCopy = ?, resourceGatedFormCtaTitle = ?, resourceGatedFormImage = ?, resourceGatedFormId = ?, resourceGatedFormPardotName = ?, resourceActive = ?, resourceGated = ?, resourceTopics = ?, resourceSubTopics = ?, resourceAreas = ?, resourceExperts = ?, resourcePageType = ?, resourceUrl = ?, resourceSlug = ?, resourceModified = ?, resourceAuthor = ?, resourceMediaLink = ?, resourceVideoTranscript = ?, resourceCid = ?, resourceSeoTitle = ?, resourceSeoDescription = ?, resourceCtaButton = ?, resourceDescription = ?, resourceIsFeatured = ?, resourceCanonicalUrl = ?, resourceCustomAuthor1 = ?, resourceCustomAuthor2 = ?, resourceCustomRelated = ? where id = ?";
                $errorUrl =
          "/index.php/dashboard/resource_library/resources/edit" . $_POST["id"];
            }
            if ($db->executeQuery($q, $v)) {
                $this->flash("success", t("Resource successfully saved"));
                return Redirect::to("/index.php/dashboard/resource_library/resources/");
            } else {
                $this->flash(
                    "flashMessage",
                    t("Your resource could not be saved at this time. Please try again.")
                );
                return Redirect::to("/index.php/dashboard/resource_library/resources/");
            }
        } else {
            $this->flash(
                "flashMessage",
                t("Please correct the issues below and try saving your resource again.")
            );
            return Redirect::to($errorUrl);
        }
    }

    public function featuredsave(): RedirectResponse
    {
        $db = \Database::connection();

        for ($x = 1; $x <= 12; $x++) {
            $output = [
        "title" => $_POST["featuredinsightname_" . $x],
        "id" => $_POST["featuredinsightid_" . $x],
        "color" => $_POST["featuredinsightcolor_" . $x],
        "image" => $_POST["featuredinsightimage_" . $x],
      ];
            $serializedOutput = serialize($output);
            $v = [$serializedOutput];
            $q =
        "update resourcelibrary_featured set resourceFeatured" .
        $x .
        " = ? where id = 1;";
            $db->executeQuery($q, $v);
        }

        $this->flash("flashMessage", t("Your featured resources have been saved."));
        return Redirect::to("/dashboard/resource_library/resources");
    }

    public function view(): void
    {
        $db = \Database::connection();
        $perPageCount = isset($_GET["num"]) ? $_GET["num"] : 50;
        $searchQueryVal = "";
        $searchQueryArray = [];
        $typeSearch =
      isset($_GET["type"]) && $_GET["type"] !== 0 && $_GET["type"] !== "0"
        ? array_push($searchQueryArray, "resourceType = " . $_GET["type"])
        : false;
        $querySearch =
      isset($_GET["q"]) && strlen($_GET["q"]) > 0
        ? array_push(
            $searchQueryArray,
            'resourceName LIKE "%' . $_GET["q"] . '%"'
        )
        : false;
        $queryStarter = "WHERE";
        foreach ($searchQueryArray as $queryItem) {
            $searchQueryVal .= $queryStarter . " " . $queryItem;
            $queryStarter = "AND";
        }
        $sql =
      "SELECT COUNT(*) as count from resourcelibrary_resource " .
      $searchQueryVal .
      ";";
        $returnItem = $db->fetchAll($sql);
        $maxCountNum = $returnItem[0]["count"];
        $maxPage = $maxCountNum / $perPageCount;
        $this->set("resourcesCount", $maxCountNum);
        $currentPage =
      isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] < $maxPage
        ? $_GET["page"]
        : 1;
        $offsetCount = $currentPage !== 1 ? ($currentPage - 1) * $perPageCount : 0;
        $resourcesQuery = $db->fetchAll(
            "SELECT * FROM resourcelibrary_resource " .
        $searchQueryVal .
        " LIMIT " .
        $perPageCount .
        " OFFSET " .
        $offsetCount,
            []
        );
        $this->set("resources", $resourcesQuery);
        $this->set("resourceTypes", $this->getTypes());
        $this->set("resourceTopics", $this->getTopics());
        $this->set("pageTitle", t("Resources"));
        $columns[0] = [
      "columnTitle" => "Resource Name",
      "dbTitle" => "resourceName",
    ];
        $columns[1] = [
      "columnTitle" => "Resource Type",
      "dbTitle" => "resourceType",
    ];
        $columns[2] = [
      "columnTitle" => "Active?",
      "dbTitle" => "resourceActive",
    ];
        $columns[3] = [
      "columnTitle" => "Actions",
      "dbTitle" => "actions",
    ];
        $this->set("columns", $columns);
        $resultNumChoices = [10, 25, 50, 100, 250];
        $this->set("resultNumChoices", $resultNumChoices);
        $prevPages = $currentPage - 3;
        $nextPages = $currentPage + 3;
        $pageRange = [];
        for ($x = $prevPages; $x < $currentPage; $x++) {
            if ($x > 0 && $x !== $currentPage) {
                array_push($pageRange, $x);
            }
        }
        array_push($pageRange, $currentPage);
        for ($x = $currentPage; $x <= $nextPages; $x++) {
            if ($x < $maxPage && $x !== $currentPage) {
                array_push($pageRange, $x);
            }
        }
        $this->set("currentPage", $currentPage);
        $this->set("paginationRange", $pageRange);
        $this->set("maxPage", $maxPage);
        $this->set("offset", $offsetCount);
    }

    public function featured(): void
    {
        $this->set("pageTitle", t("Set Featured Resources"));
        $this->set("btnClasses", ["btn-danger", "btn-primary"]);
        $db = \Database::connection();
        $resourcesQuery = $db->fetchAll(
            "SELECT id, resourceName FROM resourcelibrary_resource",
            []
        );
        $this->set("resources", $resourcesQuery);
        $featuredResourcesQuery = $db->fetchAssoc(
            "SELECT * FROM resourcelibrary_featured WHERE id = 1",
            []
        );
        for ($x = 1; $x <= 12; $x++) {
            $item = unserialize($featuredResourcesQuery["resourceFeatured" . $x]);
            $this->set("featuredinsightname_" . $x, $item["title"]);
            $this->set("featuredinsightid_" . $x, $item["id"]);
            $this->set("featuredinsightcolor_" . $x, $item["color"]);
            $this->set("featuredinsightimage_" . $x, $item["image"]);
        }
        $this->set("featuredResources", $featuredResourcesQuery);
        $this->set("types", $this->getTypes());
        $this->set("resourceContent", null);
        $this->set("resourceGatedContent", null);
        $this->set("resourceGatedFormTitle", null);
        $this->set("resourceGatedFormCopy", null);
        $this->set("resourceGatedFormCtaTitle", null);
        $this->set("resourceGatedFormImage", null);
        $this->set("resourceGatedFormId", null);
        $this->set("topics", $this->getTopics());
        $this->set("subTopics", $this->getSubTopics());
        $this->render("dashboard/resource_library/resources/featured");
    }

    public function add(): void
    {
        $this->set("pageTitle", t("Add a New Resource"));
        $this->set("id", 0);
        $this->set("btnText", "Add Resource");
        $this->set("resource", null);
        $this->set("btnClasses", ["btn-danger", "btn-primary"]);
        $this->set("resourcePageTypes", [
      "Resource Page",
      "Link to external landing page",
      "Downloadable file",
    ]);
        $this->set("readonly", null);
        $this->set("actionName", "save");
        $this->set("resourceTopicsChosen", null);
        $this->set("resourceAreasChosen", null);
        $this->set("resourceSubTopicsChosen", null);
        $this->set("resourceCanonicalUrl", null);
        $this->set("resourceTypes", $this->getTypes());
        $this->set("resourceContent", null);
        $this->set("resourceGatedContent", null);
        $this->set("resourceGatedFormTitle", null);
        $this->set("resourceGatedFormCopy", null);
        $this->set("resourceGatedFormCtaTitle", null);
        $this->set("resourceGatedFormImage", null);
        $this->set("resourceGatedFormId", null);
        $this->set("resourceTopics", $this->getTopics());
        $this->set("resourceSubTopics", $this->getSubTopics());
        $this->set("resourceCustomAuthor1", [
      "byline" => false,
      "thumb" => false,
      "url" => false,
    ]);
        $this->set("resourceCustomAuthor2", [
      "byline" => false,
      "thumb" => false,
      "url" => false,
    ]);
        $this->render("dashboard/resource_library/resources/edit");
    }

    public function edit($id = 0)
    {
        if ($id !== 0) {
            $this->set("pageTitle", t("Edit Resource"));
            $this->set("id", $id);
            $this->set("readonly", null);
            $this->set("resourcePageTypes", [
        "Resource Page",
        "Link to external landing page",
        "Downloadable file",
      ]);
            $db = \Database::connection();
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_resource WHERE id = ?",
                [$id]
            );
            $this->set("resource", $resourcesQuery);
            $this->set("btnClasses", ["btn-danger", "btn-primary"]);
            $this->set("btnText", "Edit Resource");
            $this->set(
                "resourceTopicsChosen",
                !empty($resourcesQuery["resourceTopics"])
          ? explode(",", $resourcesQuery["resourceTopics"])
          : null
            );
            $this->set(
                "resourceAreasChosen",
                !empty($resourcesQuery["resourceAreas"])
          ? explode(",", $resourcesQuery["resourceAreas"])
          : null
            );
            $this->set(
                "resourceSubTopicsChosen",
                !empty($resourcesQuery["resourceSubTopics"])
          ? explode(",", $resourcesQuery["resourceSubTopics"])
          : null
            );
            $this->set(
                "resourceAsset",
                $this->retrieveAsset($resourcesQuery["resourceAsset"])
            );
            $this->set(
                "thumbnailAsset",
                $this->retrieveThumbnail($resourcesQuery["resourceThumbnail"])
            );
            $this->set(
                "resourceCustomAuthor1",
                unserialize($resourcesQuery["resourceCustomAuthor1"])
            );
            $this->set(
                "resourceCustomAuthor2",
                unserialize($resourcesQuery["resourceCustomAuthor2"])
            );
            $this->set("actionName", "save");
            $this->set("resourceTypes", $this->getTypes());
            $this->set("resourceTopics", $this->getTopics());
            $this->set("resourceSubTopics", $this->getSubTopics());
            $this->render("dashboard/resource_library/resources/edit");
        } else {
            return Redirect::to(
                "/index.php/dashboard/resource_library/resources/add"
            );
        }
    }

    public function delete($id = 0)
    {
        $db = \Database::connection();
        if ($id !== 0) {
            $this->set("pageTitle", t("Delete Resource"));
            $this->set("id", $id);
            $this->set("readonly", "readonly");
            $this->set(
                "message",
                "Are you sure you want to delete the following resource?"
            );
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_resource WHERE id = ?",
                [$id]
            );
            $this->set("btnClasses", ["btn-primary", "btn-danger"]);
            $this->set("resource", $resourcesQuery);
            $this->set("resourceTypes", $this->getTypes());
            $this->set("resourceTopics", $this->getTopics());
            $this->set("resourceSubTopics", $this->getSubTopics());
            $this->set("actionName", "delete");
            $this->set("btnText", "Delete Resource");
            $this->render("dashboard/resource_library/resources/edit");
        } else {
            if ($_POST && $_POST["id"] !== 0) {
                $v = [$_POST["id"]];
                $q = "DELETE FROM resourcelibrary_resource WHERE id = ?";
                if ($db->executeQuery($q, $v)) {
                    $this->flash("flashMessage", t("Resource deleted."));
                } else {
                    $this->flash(
                        "flashMessage",
                        t("This resource could not be deleted. Please try again.")
                    );
                }
                return Redirect::to("/index.php/dashboard/resource_library/resources/");
            } else {
                return Redirect::to("/index.php/dashboard/resource_library/resources/");
            }
        }
    }

    public function validate($vals = false)
    {
        return true;
        // if (!$vals) {
    //     return false;
    // } else {
    //     $db = \Database::connection();
    //     if ($vals["id"] == 0) {
    //         $resourcesQuery = $db->fetchAll(
    //             "SELECT * FROM resourcelibrary_types WHERE typeName = ?",
    //             [$vals["typeName"]]
    //         );
    //         if (empty($resourcesQuery)) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         return true;
    //     }
    // }
    }

    public function getTypes()
    {
        $db = \Database::connection();
        $typesQuery = $db->fetchAll("SELECT * FROM resourcelibrary_types", []);
        return $typesQuery;
    }

    public function getTopics()
    {
        $db = \Database::connection();
        $topicsQuery = $db->fetchAll("SELECT * FROM resourcelibrary_topics", []);
        return $topicsQuery;
    }

    public function getSubTopics()
    {
        $db = \Database::connection();
        $topicsQuery = $db->fetchAll("SELECT * FROM resourcelibrary_subtopics", []);
        return $topicsQuery;
    }

    public function retrieveAsset($id = 0)
    {
        $db = \Database::connection();
        $assetQuery = $db->fetchAssoc(
            "SELECT * FROM resourcelibrary_assets WHERE id = ?",
            [$id]
        );
        return $assetQuery;
    }

    public function retrieveResource($id = 0)
    {
        $db = \Database::connection();
        $resourceQuery = $db->fetchAssoc(
            "SELECT * FROM resourcelibrary_resource WHERE id = ?",
            [$id]
        );
        return $resourceQuery;
    }

    public function retrieveThumbnail($id = 0)
    {
        $db = \Database::connection();
        $assetQuery = $db->fetchAssoc(
            "SELECT * FROM resourcelibrary_thumbnails WHERE id = ?",
            [$id]
        );
        return $assetQuery;
    }

    public function createNewAsset()
    {
        ini_set("upload_max_filesize", "25M");
        ini_set("post_max_size", "25M");
        ini_set("max_input_time", 300);
        ini_set("max_execution_time", 300);
        if (!file_exists("application/files/resources/assets")) {
            mkdir("application/files/resources/assets", 0755, true);
        }
        move_uploaded_file(
            $_FILES["fileName"]["tmp_name"],
            "application/files/resources/assets/" . $_FILES["fileName"]["name"]
        );
        $fileType = pathinfo($_FILES["fileName"]["name"], PATHINFO_EXTENSION);
        $db = \Database::connection();
        $v = [$_FILES["fileName"]["name"], $fileType];
        $q =
      "insert into resourcelibrary_assets ( fileName,fileType ) values (?,?)";
        $result = $db->executeQuery($q, $v);
        return $db->Insert_ID();
    }

    public function createNewThumbnail()
    {
        ini_set("upload_max_filesize", "25M");
        ini_set("post_max_size", "25M");
        ini_set("max_input_time", 300);
        ini_set("max_execution_time", 300);
        if (!file_exists("application/files/resources/thumbnails")) {
            mkdir("application/files/resources/thumbnails", 0755, true);
        }
        move_uploaded_file(
            $_FILES["resourceThumbnail"]["tmp_name"],
            "application/files/resources/thumbnails/" .
        $_FILES["resourceThumbnail"]["name"]
        );
        $fileType = pathinfo(
            $_FILES["resourceThumbnail"]["name"],
            PATHINFO_EXTENSION
        );
        $db = \Database::connection();
        $v = [$_FILES["resourceThumbnail"]["name"], $fileType];
        $q =
      "insert into resourcelibrary_thumbnails ( fileName,fileType ) values (?,?)";
        $result = $db->executeQuery($q, $v);
        return $db->Insert_ID();
    }

    public function doesSlugExist($slug)
    {
        $db = \Database::connection();
        $resourcesQuery = $db->fetchAll(
            "SELECT * FROM resourcelibrary_resource WHERE resourceSlug LIKE ?",
            [$slug]
        );
        return count($resourcesQuery);
    }

    public function slugify($text, string $divider = "-")
    {
        // replace non letter or digits by divider
        $text = preg_replace("~[^\pL\d]+~u", $divider, $text);

        // transliterate
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);

        // remove unwanted characters
        $text = preg_replace("~[^-\w]+~", "", $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace("~-+~", $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return "n-a";
        }

        return $text;
    }
}
