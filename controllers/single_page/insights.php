<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage;

use PageController;
use Database;
use Core;
use Loader;
use Page;
use Request;
use Concrete\Core\Routing\Redirect;
use Concrete\Package\PropertyTaxRates\EntityFactory;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Url;
use View;

class Insights extends PageController
{
    public function on_start()
    {
        $this->addFooterItem(
            '<script type="text/javascript" src="/js/main.js"></script>'
        );
        $this->addHeaderItem(
            '<link rel="stylesheet" href="/styles/resources.css">'
        );
    }

    public function view($resourcePath = null, $resourceSlug = null)
    {
        $icons = $this->setInsightIcons();
        if ($resourceSlug == null || $resourcePath == "page") {
            $db = \Database::connection();
            $this->requireAsset("javascript", "jquery");
            $this->requireAsset("css", "font-awesome");
            $this->set("customPageTitle", "Insights Center");
            $sql =
        "SELECT COUNT(*) as pagecount FROM resourcelibrary_resource WHERE resourcelibrary_resource.resourceActive = 1;";
            $countReturn = $db->fetchAssoc($sql);
            $totalItems = $countReturn["pagecount"];
            $totalPages = $totalItems / 8 + 1;
            $pageFinder = false;
            if (
        $resourcePath &&
        $resourcePath == "page" &&
        $totalPages > intVal($resourceSlug)
      ) {
                $pageFinder = true;
                $pageUrlOffset = (intVal($resourceSlug) - 1) * 8;
                $nextPageNum = $resourceSlug + 1;
                $this->set("noindex", true);
            } else {
                $pageUrlOffset = false;
                $nextPageNum = 1;
                $this->set("noindex", false);
            }
            $allResources = $this->getResourceItems($pageUrlOffset);
            $featuredResources = $this->getFeaturedResourceItems();
            $this->set("pageCrawl", $nextPageNum);
            // usort($allResources, function($a, $b) {
            //     return $a['resourceDate'] <=> $b['resourceDate'];
            // });
            $this->set("resources", $allResources);
            $this->set("featuredResources", $featuredResources);
            $sql = "SELECT * from resourcelibrary_types ORDER BY typeName ASC;";
            $returnItem = $db->fetchAll($sql);
            $this->set("types", $returnItem);
            $sql = "SELECT * from resourcelibrary_topics ORDER BY topicName ASC;";
            $returnItem = $db->fetchAll($sql);
            $this->set("topics", $returnItem);
            $sql =
        "SELECT * from resourcelibrary_subtopics ORDER BY subTopicName ASC;";
            $returnItem = $db->fetchAll($sql);
            $returnItem = $db->fetchAll($sql);
            $this->set("resourceview", false);
            $this->set("subtopics", $returnItem);
            if ($pageFinder) {
                $this->set(
                    "title",
                    "Page " . intVal($resourceSlug) . " | Insights Center"
                );
            }
        } else {
            $this->set("resourceview", true);
            $thumbUrl = "/img/default1.png";
            $db = \Database::connection();
            $sql =
        "SELECT resourcelibrary_resource.id, resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceVideoTranscript, resourcelibrary_resource.resourceCanonicalUrl, resourcelibrary_resource.resourceCreated, resourcelibrary_resource.resourceAuthor, resourcelibrary_resource.resourceSeoTitle, resourcelibrary_resource.resourceSeoDescription, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceGated, resourcelibrary_resource.resourceGatedFormTitle, resourcelibrary_resource.resourceGatedFormPardotName, resourcelibrary_resource.resourceGatedFormCopy, resourcelibrary_resource.resourceGatedFormCtaTitle, resourcelibrary_resource.resourceGatedFormImage, resourcelibrary_resource.resourceGatedFormId, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceCustomAuthor1, resourcelibrary_resource.resourceCustomAuthor2, resourcelibrary_resource.resourceCustomRelated, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.resourceExperts, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceAreas, resourcelibrary_resource.resourceSubTopics, resourcelibrary_types.typeName, resourcelibrary_assets.fileName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_assets ON resourcelibrary_assets.id = resourcelibrary_resource.resourceAsset LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceActive = 1 AND resourcelibrary_resource.resourceSlug = '" .
        $resourceSlug .
        "'";
            $returnItem = $db->fetchAssoc($sql);
            if (empty($returnItem)) {
                $this->replace("/page_not_found");
            } else {
                $urlSection =
          (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on"
            ? "https"
            : "http") . "://$_SERVER[HTTP_HOST]";
                $resourceUrl = $returnItem["resourceUrl"];
                $resourceCid = $returnItem["resourceCid"];
                $resourcePage = $returnItem["resourceCid"]
          ? $urlSection .
            "" .
            Page::getByID($returnItem["resourceCid"])->getCollectionPath()
          : false;
                if (!$resourcePage) {
                    $resourcePage = $returnItem["resourceUrl"]
            ? $returnItem["resourceUrl"]
            : false;
                }
                if ($resourcePage) {
                    return Redirect::to($resourcePage);
                }
                $this->set("thisResource", $returnItem);
                $seoName =
          $returnItem["resourceSeoTitle"] &&
          strlen($returnItem["resourceSeoTitle"]) > 0
            ? $returnItem["resourceSeoTitle"]
            : $returnItem["resourceName"];
                $this->set("customPageTitle", $seoName . " | Insights Center");
                $thumbUrl = $returnItem["thumbnailFile"]
          ? "/application/files/resources/thumbnails/" .
            $returnItem["thumbnailFile"]
          : "/default1.png";
                $this->set("resourceThumbnail", $thumbUrl);
                $this->set("isNoHero", true);
                $this->set(
                    "resourceId",
                    isset($returnItem["id"]) ? $returnItem["id"] : false
                );
                $gatedResourceInfo = [
          "isGated" => $returnItem["resourceGated"],
          "gatedFormTitle" => $returnItem["resourceGatedFormTitle"],
          "gatedFormCopy" => $returnItem["resourceGatedFormCopy"],
          "gatedFormCtaTitle" => $returnItem["resourceGatedFormCtaTitle"],
          "gatedFormImageId" => $returnItem["resourceGatedFormImage"],
          "gatedFormCustomId" => $returnItem["resourceGatedFormId"],
          "gatedFormPardotName" => $returnItem["resourceGatedFormPardotName"],
        ];
                $this->set(
                    "resourceGatedInfo",
                    isset($returnItem["id"]) ? $gatedResourceInfo : false
                );
                if ($gatedResourceInfo["isGated"] == 1) {
                    $isLoggedIn = false;
                    // $isLoggedIn = User::isLoggedIn();
                    $v = View::getInstance();
                    $html = Loader::helper("html");
                    ob_start();
                    include "views/gated_content_form.php";
                    $contents = ob_get_contents();
                    ob_end_clean();
                    $v->addFooterItem($contents);
                    $v->addHeaderItem(
                        '<link rel="stylesheet" href="/packages/company/themes/company/js/pardot/pardot-form.css">',
                        "theme_name"
                    );
                    $v->addFooterItem(
                        $html->javascript("/concrete/js/jquery.js"),
                        "theme_name"
                    );
                    $v->addFooterItem(
                        $html->javascript(
                "/packages/company/themes/company/js/pardot/pardot-form.js"
            ),
                        "theme_name"
                    );
                }
                if (
          !$returnItem ||
          !array_key_exists("resourceActive", $returnItem) ||
          intVal($returnItem["resourceActive"]) == 0
        ) {
                    $pageTitle = "Page Not Found";
                    $resourceSeoDescription = false;
                    $thumbUrl = false;
                } else {
                    $pageTitle = $returnItem["resourceName"];
                    if (
            isset($returnItem["resourceCanonicalUrl"]) &&
            strlen($returnItem["resourceCanonicalUrl"]) > 0
          ) {
                        $canonicalUrl = $returnItem["resourceCanonicalUrl"];
                        $this->set("customCanonicalUrl", $canonicalUrl);
                    }
                    $resourceSeoDescription =
            isset($returnItem["resourceDescription"]) &&
            strlen($returnItem["resourceDescription"])
              ? $returnItem["resourceDescription"]
              : false;
                }
                $this->set("title", $pageTitle);
                $this->set("resourceDescription", $resourceSeoDescription);
            }
            $topicsFind = $returnItem["resourceTopics"];
            $areasFind = $returnItem["resourceAreas"];
            $relatedResourcesObj = false;
            if (
        isset($returnItem["resourceCustomRelated"]) &&
        $returnItem["resourceCustomRelated"] !== null &&
        count(explode(",", $returnItem["resourceCustomRelated"])) > 1
      ) {
                $customRelatedItems = explode(
                    ",",
                    $returnItem["resourceCustomRelated"]
                );
                foreach ($customRelatedItems as $customRelatedItem) {
                    $relatedResourcesId[] = ["id" => $customRelatedItem, "count" => 0];
                }
                $relatedResourcesObj = $relatedResourcesId;
            } else {
                if (isset($returnItem["id"])) {
                    $relatedResourcesObj = $this->findRelatedInsights(
                        $topicsFind,
                        $areasFind,
                        $returnItem["id"]
                    );
                } else {
                    $relatedResourcesObj = false;
                }
            }
            $this->set("relatedInsights", $relatedResourcesObj);
        }
    }

    public function findRelatedInsights($topics, $areas, $currentResourceId)
    {
        $db = \Database::connection();
        $whereStatement = "WHERE";
        $mostRecentDate = "2021-01-01 00:00:00";
        $extraQueryString = null;
        $topicsArray = false;
        $areasArray = false;
        $extraQuery = [
      "resourcelibrary_resource.resourceActive = 1",
      "resourcelibrary_resource.id != " . $currentResourceId,
      "resourcelibrary_resource.resourceCreated > '" . $mostRecentDate . "'",
    ];
        if ($topics || $areas) {
            $orStatement = "";
            if ($topics && $areas) {
                $orStatement .= "(";
            }
            if ($topics) {
                $topicsArray = explode(",", $topics);
                foreach ($topicsArray as $key => $topicId) {
                    if ($key == 0) {
                        $orStatement .= "(";
                    }
                    if ($key !== 0) {
                        $orStatement .= " OR ";
                    }
                    $orStatement .=
            'CONCAT(",",resourcelibrary_resource.resourceTopics,",") LIKE "%,' .
            $topicId .
            ',%"';
                }
                if (count($topicsArray) > 0) {
                    $orStatement .= ")";
                }
            }
            if ($areas) {
                if ($orStatement !== "") {
                    $orStatement .= " OR ";
                }
                $areasArray = explode(",", $areas);
                foreach ($areasArray as $key => $areaId) {
                    if ($key == 0) {
                        $orStatement .= "(";
                    }
                    if ($key !== 0) {
                        $orStatement .= " OR ";
                    }
                    $orStatement .=
            'CONCAT(",",resourcelibrary_resource.resourceAreas,",") LIKE "%,' .
            $areaId .
            ',%"';
                }
                if (count($areasArray) > 0) {
                    $orStatement .= ")";
                }
            }
            if ($topics && $areas) {
                $orStatement .= ")";
            }
            array_push($extraQuery, $orStatement);
        }
        for ($x = 0; $x < count($extraQuery); $x++) {
            $whereStatement = $x == 0 ? "WHERE" : " AND";
            $extraQueryString .= $whereStatement . " " . $extraQuery[$x];
        }
        $sql =
      "SELECT resourcelibrary_resource.id, resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceThumbnail, resourcelibrary_resource.resourceCid, resourcelibrary_resource.id AS resourceId, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourceDescription AS resourceDescription, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAreas, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceCustomAuthor1, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCreated, resourcelibrary_types.typeName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail " .
      $extraQueryString .
      " ORDER BY resourcelibrary_resource.resourceCreated DESC;";
        $returnItem = $db->fetchAll($sql);
        $relatedResourcesId = [];
        foreach ($returnItem as $resource) {
            $matchCount = 0;
            $resourceTopics = explode(",", $resource["resourceTopics"]);
            $resourceAreas = explode(",", $resource["resourceAreas"]);
            if ($topicsArray) {
                $matchCount =
          $matchCount + count(array_intersect($topicsArray, $resourceTopics));
            }
            if ($areasArray) {
                $matchCount =
          $matchCount + count(array_intersect($areasArray, $resourceAreas));
            }
            $relatedResourcesId[] = ["id" => $resource["id"], "count" => $matchCount];
        }
        usort($relatedResourcesId, function ($a, $b) {
            if ($a["count"] == $b["count"]) {
                return 0;
            }
            return $a["count"] < $b["count"] ? 1 : -1;
        });
        return $relatedResourcesId;
    }

    public function setInsightIcons()
    {
        $this->set(
            "article_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20.751" height="24.884" viewBox="0 0 20.751 24.884"><defs><style>.a{fill:#4e565b;}.b{clip-path:url(#a);}</style><clipPath id="a"><rect class="a" width="20.751" height="24.884"/></clipPath></defs><g class="b"><path class="a" d="M19.29,10.117l-.137-.4c-.052-.153-.1-.32-.154-.5-.048.182-.1.35-.148.505s-.1.289-.143.4l-.6,1.776h1.78Z" transform="translate(-8.525 -4.34)"/><path class="a" d="M17.573,0H3.178A3.178,3.178,0,0,0,0,3.178V21.706a3.178,3.178,0,0,0,3.178,3.178h14.4a3.178,3.178,0,0,0,3.178-3.178V3.178A3.178,3.178,0,0,0,17.573,0M9.7,3.536h1.548l2.523,6.519H12.6a.543.543,0,0,1-.324-.092.475.475,0,0,1-.177-.235l-.385-1.136H9.235L8.85,9.728a.511.511,0,0,1-.175.226.5.5,0,0,1-.318.1H7.177Zm.675,17.725H4.246V19.673h6.13ZM16.5,17.608H4.246V16.021H16.5Zm0-3.579H4.246V12.442H16.5Z" transform="translate(0 0)"/></g></svg>'
        );
        $this->set(
            "blog_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25.703" height="25.703" viewBox="0 0 25.703 25.703"><defs><style>.a{fill:#4e565b;}.b{clip-path:url(#a);}</style><clipPath id="a"><rect class="a" width="25.703" height="25.703"/></clipPath></defs><g class="b"><path class="a" d="M22.152,0H3.551A3.551,3.551,0,0,0,0,3.551v18.6A3.551,3.551,0,0,0,3.551,25.7h18.6A3.551,3.551,0,0,0,25.7,22.152V3.551A3.551,3.551,0,0,0,22.152,0M10.589,2.346A1.182,1.182,0,1,1,9.407,3.529a1.183,1.183,0,0,1,1.182-1.183m-3.431,0A1.182,1.182,0,1,1,5.975,3.529,1.183,1.183,0,0,1,7.158,2.346m-3.431,0A1.182,1.182,0,1,1,2.544,3.529,1.183,1.183,0,0,1,3.726,2.346M23.5,22.152A1.353,1.353,0,0,1,22.152,23.5H3.551A1.352,1.352,0,0,1,2.2,22.152V6.9H23.5Z" transform="translate(0 0)"/><rect class="a" width="6.371" height="1.65" transform="translate(6.097 18.066)"/><rect class="a" width="12.742" height="1.65" transform="translate(6.097 14.269)"/><rect class="a" width="12.742" height="1.65" transform="translate(6.097 10.549)"/></g></svg>'
        );
        $this->set(
            "playbook_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" width="21.542" height="24.619" viewBox="0 0 21.542 24.619"><defs><style>.a{fill:#4e565b;}</style></defs><path class="a" d="M4.616,0A4.617,4.617,0,0,0,0,4.616V20a4.617,4.617,0,0,0,4.616,4.616H20a1.539,1.539,0,1,0,0-3.077V18.465a1.537,1.537,0,0,0,1.539-1.539V1.539A1.537,1.537,0,0,0,20,0H4.616Zm0,18.465h12.31v3.077H4.616a1.539,1.539,0,1,1,0-3.077ZM6.155,6.924a.772.772,0,0,1,.769-.769h9.232a.769.769,0,1,1,0,1.539H6.924A.772.772,0,0,1,6.155,6.924Zm.769,2.308h9.232a.769.769,0,1,1,0,1.539H6.924a.769.769,0,1,1,0-1.539Z"/></svg>'
        );
        $this->set(
            "podcast_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="28.577" viewBox="0 0 25 28.577"><defs><style>.a{fill:#4e565b;}</style></defs><path class="a" d="M17.824,20.759a9.821,9.821,0,1,0-10.647,0c.067.965.223,2.121.4,3.186L7.589,24a12.5,12.5,0,1,1,9.821,0l.011-.061c.173-1.071.335-2.221.4-3.181ZM17.7,18.633a3.6,3.6,0,0,0-.391-.9,3.8,3.8,0,0,0-1.222-1.25,5.357,5.357,0,1,0-7.165,0A3.8,3.8,0,0,0,7.7,17.729a3.6,3.6,0,0,0-.391.9,8.036,8.036,0,1,1,10.391,0Zm-5.2-1.222c1.836,0,3.571.48,3.571,2.444a40.843,40.843,0,0,1-1.15,7.416c-.285,1.06-1.367,1.306-2.422,1.306s-2.132-.246-2.422-1.306a40.006,40.006,0,0,1-1.15-7.411c0-1.959,1.735-2.444,3.571-2.444Zm0-1.786A3.125,3.125,0,1,1,15.625,12.5,3.127,3.127,0,0,1,12.5,15.625Z"/></svg>'
        );
        $this->set(
            "report_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.108" height="25.312" viewBox="0 0 21.108 25.312"><defs><style>.a{fill:#4e565b;}.b{clip-path:url(#a);}</style><clipPath id="a"><rect class="a" width="21.108" height="25.312"/></clipPath></defs><g class="b"><path class="a" d="M18.578,10.707a.969.969,0,0,0,.362-.221.847.847,0,0,0,.205-.33,1.28,1.28,0,0,0,.064-.408.855.855,0,0,0-.284-.683,1.326,1.326,0,0,0-.881-.245h-.619v1.966h.619a1.593,1.593,0,0,0,.535-.08" transform="translate(-8.044 -4.072)"/><path class="a" d="M17.876,0H3.233A3.232,3.232,0,0,0,0,3.232V22.08a3.232,3.232,0,0,0,3.233,3.233H17.876a3.232,3.232,0,0,0,3.232-3.233V3.232A3.232,3.232,0,0,0,17.876,0M7.842,3.6H10a4.436,4.436,0,0,1,1.227.148,2.308,2.308,0,0,1,.828.412,1.561,1.561,0,0,1,.467.623,2.069,2.069,0,0,1,.145.783,2.1,2.1,0,0,1-.087.61,1.847,1.847,0,0,1-.257.53,2.01,2.01,0,0,1-.419.434,2.216,2.216,0,0,1-.571.314,1.462,1.462,0,0,1,.289.191,1.155,1.155,0,0,1,.235.278l1.41,2.307H11.874a.59.59,0,0,1-.555-.291l-1.1-1.925a.464.464,0,0,0-.171-.182.569.569,0,0,0-.275-.054H9.38v2.453H7.842Zm2.712,18.03H4.319V20.012h6.235Zm6.235-3.716H4.319V16.3h12.47Zm0-3.64H4.319V12.656h12.47Z" transform="translate(0 0)"/></g></svg>'
        );
        $this->set(
            "video_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" width="22.543" height="15.028" viewBox="0 0 22.543 15.028"><defs><style>.a{fill:#4e565b;}</style></defs><path class="a" d="M0,66.5A2.507,2.507,0,0,1,2.5,64H12.524a2.507,2.507,0,0,1,2.5,2.5V76.524a2.507,2.507,0,0,1-2.5,2.5H2.5a2.507,2.507,0,0,1-2.5-2.5Zm21.882-1.1a1.253,1.253,0,0,1,.661,1.1V76.524a1.253,1.253,0,0,1-1.949,1.041l-3.757-2.5-.556-.372V68.34l.556-.372,3.757-2.5A1.257,1.257,0,0,1,21.882,65.4Z" transform="translate(0 -64)"/></svg>'
        );
        $this->set(
            "webinar_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27" height="22.177" viewBox="0 0 27 22.177"><defs><style>.a{fill:#4e565b;}.b{clip-path:url(#a);}</style><clipPath id="a"><rect class="a" width="27" height="22.177"/></clipPath></defs><g class="b"><path class="a" d="M23.973,0H3.026A3.027,3.027,0,0,0,0,3.027V14.744a3.027,3.027,0,0,0,3.026,3.027h9.318v2.1H6.973v2.311H20.026V19.866H14.655v-2.1h9.318A3.027,3.027,0,0,0,27,14.744V3.027A3.027,3.027,0,0,0,23.973,0m.715,14.744a.716.716,0,0,1-.715.715H19.084l-.166-1.144a2.084,2.084,0,0,0-1.292-1.638l-2.106-.838A.824.824,0,0,1,15,11.073v-.047a.824.824,0,0,1,.173-.505,4.753,4.753,0,0,0,1.04-2.737c0-1.63-.832-3.259-2.545-3.259a2.946,2.946,0,0,0-2.829,3.19,5.174,5.174,0,0,0,.96,2.852.824.824,0,0,1-.368,1.238l-2.125.849a2.084,2.084,0,0,0-1.3,1.724L7.9,15.459H3.026a.717.717,0,0,1-.715-.715V3.027a.716.716,0,0,1,.715-.715H23.973a.716.716,0,0,1,.715.715Z" transform="translate(0 0)"/></g></svg>'
        );
        $this->set(
            "whitepaper_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.542" height="25.832" viewBox="0 0 21.542 25.832"><defs><style>.a{fill:#4e565b;}.b{clip-path:url(#a);}</style><clipPath id="a"><rect class="a" width="21.542" height="25.832"/></clipPath></defs><g class="b"><path class="a" d="M27.3,9.371a.771.771,0,0,0-.293-.173,1.285,1.285,0,0,0-.419-.061h-.668v1.754h.668a.814.814,0,0,0,.942-.907.989.989,0,0,0-.057-.346.7.7,0,0,0-.173-.268" transform="translate(-11.68 -4.117)"/><path class="a" d="M18.243,0H3.3A3.3,3.3,0,0,0,0,3.3V22.533a3.3,3.3,0,0,0,3.3,3.3H18.243a3.3,3.3,0,0,0,3.3-3.3V3.3a3.3,3.3,0,0,0-3.3-3.3M5.486,4.06a.488.488,0,0,1,.279.076.351.351,0,0,1,.146.2l.759,2.886q.037.14.076.3a2.7,2.7,0,0,1,.057.334c.025-.119.053-.231.083-.336s.059-.2.088-.3l.9-2.886a.4.4,0,0,1,.146-.192.437.437,0,0,1,.271-.085h.38a.476.476,0,0,1,.275.074.383.383,0,0,1,.15.2l.888,2.886q.042.133.087.283a3.148,3.148,0,0,1,.08.321c.02-.112.041-.218.063-.319s.044-.2.066-.285l.759-2.886a.362.362,0,0,1,.144-.193.45.45,0,0,1,.273-.084h1.01L10.756,9.591H9.594L8.554,6.163c-.018-.053-.036-.111-.055-.175s-.037-.13-.055-.2c-.018.071-.036.138-.055.2s-.038.121-.055.175L7.278,9.591H6.116L4.408,4.06Zm5.285,18.011H4.408V20.423h6.363Zm6.363-3.792H4.408V16.631H17.134Zm0-3.715H4.408V12.916H17.134ZM17,6.626a1.52,1.52,0,0,1-.407.592A1.9,1.9,0,0,1,15.9,7.6a3.179,3.179,0,0,1-.993.139h-.668V9.591H12.958V4.06h1.952a3.225,3.225,0,0,1,1,.138,1.931,1.931,0,0,1,.691.382,1.487,1.487,0,0,1,.4.573,1.94,1.94,0,0,1,.129.714A2.1,2.1,0,0,1,17,6.626" transform="translate(0 0)"/></g></svg>'
        );
        $this->set(
            "casestudy_icon",
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27.479" height="28.481" viewBox="0 0 27.479 28.481"><defs><clipPath id="a"><rect width="27.479" height="28.481" transform="translate(0 0)" fill="none"/></clipPath></defs><g transform="translate(0 0)"><g clip-path="url(#a)"><path d="M26.952,25.412l-5.69-5.69a12.1,12.1,0,1,0-2.7,2.388l5.845,5.845a1.8,1.8,0,0,0,2.543-2.543M12.027,3.6a8.422,8.422,0,0,1,6.4,13.9,2.465,2.465,0,0,0-1.518-1.9l-2.493-.993a.975.975,0,0,1-.615-.906v-.056a.976.976,0,0,1,.2-.6,5.625,5.625,0,0,0,1.232-3.24c0-1.929-.986-3.858-3.014-3.858A3.488,3.488,0,0,0,8.879,9.725,6.131,6.131,0,0,0,10.015,13.1a.975.975,0,0,1-.435,1.467L7.065,15.574a2.454,2.454,0,0,0-1.5,1.865A8.424,8.424,0,0,1,12.027,3.6" fill="#4e565b"/></g></g></svg>'
        );
    }

    public function getFeaturedResourceItems()
    {
        $db = \Database::connection();
        $featuredResourcesQuery = $db->fetchAssoc(
            "SELECT * FROM resourcelibrary_featured WHERE id = 1",
            []
        );
        for ($x = 1; $x <= 12; $x++) {
            $item = unserialize($featuredResourcesQuery["resourceFeatured" . $x]);
            $itemId = $item["id"];
            $sql =
        "SELECT resourcelibrary_resource.id, resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceSeoTitle, resourcelibrary_resource.resourceSeoDescription, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceGated, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourceCreated, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceExperts, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCustomAuthor1, resourcelibrary_resource.resourceCustomAuthor2, resourcelibrary_types.typeName, resourcelibrary_assets.fileName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_assets ON resourcelibrary_assets.id = resourcelibrary_resource.resourceAsset LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceActive = 1 AND resourcelibrary_resource.id = '" .
        $itemId .
        "'";
            $returnItem = $db->fetchAll($sql);
            foreach ($returnItem as $resource) {
                $thumbnailClasses = "";
                $thumbUrlInstance = str_replace(" ", "%20", $resource["thumbnailFile"]);
                if ($resource["resourceUrl"] && $resource["resourceUrl"] !== "") {
                    $resourceUrl = $resource["resourceUrl"];
                    $linkTarget = "_blank";
                } else {
                    $resourcePage = $resource["resourceCid"]
            ? Page::getByID($resource["resourceCid"])
            : false;
                    $delimiter = "-";
                    $str = $resource["typeName"];
                    $linkTarget =
            $resource["resourceType"] == 1 || $resource["resourceType"] == 2
              ? "_blank"
              : "_self";
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
            : "/insights/" . $slugType . "/" . $resource["resourceSlug"];
                }
                $thumbUrl = "/img/default1.png";
                if ($thumbUrlInstance && $thumbUrlInstance !== "") {
                    $thumbUrl =
            "/application/files/resources/thumbnails/" . $thumbUrlInstance;
                } elseif ($resourcePage && $resourcePage->getAttribute("thumbnail")) {
                    $thumbnail = $resourcePage->getAttribute("thumbnail");
                    if (is_object($thumbnail)) {
                        $thumbUrl = $thumbnail->getRelativePath();
                    } else {
                        $thumbUrl = "/img/default2.png";
                    }
                }
                if (isset($resource["resourceDescription"])) {
                    $description = $this->truncateText(
                        $resource["resourceDescription"],
                        250
                    );
                } elseif (
          isset($resourcePage) &&
          $resourcePage &&
          $resourcePage->getCollectionDescription()
        ) {
                    $description = $this->truncateText(
                        $resourcePage->getCollectionDescription(),
                        250
                    );
                }
                if ($item["color"] == 2) {
                    $thumbnailClasses .= " gray";
                } else {
                    $thumbnailClasses .= " white";
                }
                if ($item["image"] == 4) {
                    $thumbnailClasses .= " img";
                } elseif ($item["image"] == 3) {
                    $thumbnailClasses .= " thumbnailimg";
                } elseif ($item["image"] == 2) {
                    $thumbnailClasses .= " icon";
                    if ($resource["resourceType"] == 3) {
                        if ($item["color"] == 2) {
                            $thumbUrl = "/img/icons/icon-video-white.svg";
                        } else {
                            $thumbUrl = "/img/icons/icon-video.svg";
                        }
                    } elseif ($resource["resourceType"] == 4) {
                        if ($item["color"] == 2) {
                            $thumbUrl = "/img/icons/icon-article-white.svg";
                        } else {
                            $thumbUrl = "/img/icons/icon-article.svg";
                        }
                    } else {
                        $thumbUrl = "/img/icons/icon-video.svg";
                    }
                } else {
                    $thumbnailClasses .= " none";
                }
                $resourceExpertsItem = explode(",", $resource["resourceExperts"]);
                $resourceExpert =
          count($resourceExpertsItem) > 0 ? $resourceExpertsItem[0] : false;
                $resourceSvgSlugName = str_replace(" ", "", $resource["typeName"]);
                $regularOutput[] = [
          "featuredOrder" => $x,
          "id" => isset($resource["resourceId"]) ? $resource["resourceId"] : 0,
          "featuredColor" => $item["color"],
          "featuredImage" => $item["image"],
          "resourceSlug" => $resource["resourceSlug"],
          "resourceType" => $resource["resourceType"],
          "resourceTopics" => $resource["resourceTopics"],
          "resourceSubTopics" => $resource["resourceSubTopics"],
          "typeName" => $resource["typeName"],
          "iconName" => strtolower(
              preg_replace("/[^A-Za-z0-9 ]/", "", $resourceSvgSlugName)
          ),
          "resourceName" => $resource["resourceName"],
          "resourceThumbnail" => $thumbUrl,
          "resourceDate" => date(
              "M j, Y",
              strtotime($resource["resourceCreated"])
          ),
          "resourceDescription" => isset($description) ? $description : null,
          "resourceCid" => $resource["resourceCid"],
          "resourceMediaLink" => $resource["resourceMediaLink"],
          "resourceUrl" => $resourceUrl,
          "resourceTarget" => $linkTarget,
          "isGated" => $resource["resourceGated"],
          "thumbnailClasses" => $thumbnailClasses,
          "resourceExpert" => $resourceExpert,
          "resourceCustomAuthor1" => $resource["resourceCustomAuthor1"],
          "resourceCustomAuthor2" => $resource["resourceCustomAuthor2"],
        ];
            }
        }
        $output = $regularOutput;
        return $output;
    }

    public function getResourceItems($pageUrlOffset = false)
    {
        $mostRecentDate = "2021-01-01 00:00:00";
        $db = \Database::connection();
        $featuredResourcesQuery = $db->fetchAll(
            "SELECT * FROM resourcelibrary_resource WHERE resourceIsFeatured = 1 ORDER BY resourceCreated DESC LIMIT 8",
            []
        );
        $featuredResourceList = [];
        foreach ($featuredResourcesQuery as $featuredResource) {
            array_push($featuredResourceList, $featuredResource["id"]);
        }
        $featuredResourceItems = implode(",", $featuredResourceList);
        if (!$pageUrlOffset) {
            $sql =
        "SELECT resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceIsFeatured, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.id AS resourceId, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourceDescription AS resourceDescription, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceCustomAuthor1, resourcelibrary_resource.resourceCustomAuthor2, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCreated, resourcelibrary_types.typeName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceActive = 1 AND resourcelibrary_resource.resourceCreated > '" .
        $mostRecentDate .
        "' ORDER BY resourcelibrary_resource.resourceIsFeatured DESC, resourcelibrary_resource.resourceCreated DESC LIMIT 9;";
        } else {
            $sql =
        "SELECT resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceIsFeatured, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.id AS resourceId, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourceDescription AS resourceDescription, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCreated, resourcelibrary_types.typeName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceActive = 1 AND resourcelibrary_resource.resourceCreated > '" .
        $mostRecentDate .
        "' ORDER BY resourcelibrary_resource.resourceCreated DESC LIMIT 9 OFFSET " .
        $pageUrlOffset .
        ";";
        }
        $returnItem = $db->fetchAll($sql);
        foreach ($returnItem as $resource) {
            $thumbUrlInstance = str_replace(" ", "%20", $resource["thumbnailFile"]);
            if ($resource["resourceUrl"] && $resource["resourceUrl"] !== "") {
                $resourceUrl = $resource["resourceUrl"];
                $linkTarget = "_blank";
            } else {
                $resourcePage = $resource["resourceCid"]
          ? Page::getByID($resource["resourceCid"])
          : false;
                $delimiter = "-";
                $str = $resource["typeName"];
                $linkTarget =
          $resource["resourceType"] == 1 || $resource["resourceType"] == 2
            ? "_blank"
            : "_self";
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
          : "/insights/" . $slugType . "/" . $resource["resourceSlug"];
            }
            $thumbUrl = "/img/default1.png";
            $thumbnailClasses = "thumbnailimg";
            if ($thumbUrlInstance && $thumbUrlInstance !== "") {
                $thumbUrl =
          "/application/files/resources/thumbnails/" . $thumbUrlInstance;
            } elseif ($resourcePage && $resourcePage->getAttribute("thumbnail")) {
                $thumbnail = $resourcePage->getAttribute("thumbnail");
                if (is_object($thumbnail)) {
                    $thumbUrl = $thumbnail->getRelativePath();
                } else {
                    $thumbUrl = "/img/default1.png";
                }
            } else {
                if ($resource["resourceType"] == 2) {
                    $thumbUrl = "/img/default1.png";
                } elseif ($resource["resourceType"] == 3) {
                    $thumbUrl = "/img/default1.png";
                } elseif ($resource["resourceType"] == 1) {
                    $thumbUrl = "/img/Blog.png";
                } else {
                    $thumbUrl = "/img/default1.png";
                }
                $thumbnailClasses = "thumbnailimg icon";
            }
            if ($resource["resourceDescription"]) {
                $description = $this->truncateText(
                    $resource["resourceDescription"],
                    250
                );
            } elseif ($resourcePage && $resourcePage->getCollectionDescription()) {
                $description = $this->truncateText(
                    $resourcePage->getCollectionDescription(),
                    250
                );
            }
            $resourceSvgSlugName = str_replace(" ", "", $resource["typeName"]);
            $regularOutput[] = [
        "id" => $resource["resourceId"],
        "featuredId" => $resource["resourceIsFeatured"],
        "resourceSlug" => $resource["resourceSlug"],
        "resourceType" => $resource["resourceType"],
        "resourceTopics" => $resource["resourceTopics"],
        "resourceSubTopics" => $resource["resourceSubTopics"],
        "typeName" => $resource["typeName"],
        "resourceName" => $resource["resourceName"],
        "resourceThumbnail" => $thumbUrl,
        "resourceDate" => strtotime($resource["resourceCreated"]),
        "resourceDescription" => isset($description) ? $description : null,
        "resourceCid" => $resource["resourceCid"],
        "resourceMediaLink" => $resource["resourceMediaLink"],
        "resourceUrl" => $resourceUrl,
        "resourceTarget" => $linkTarget,
        "thumbnailClasses" => $thumbnailClasses,
        "iconName" => strtolower(
            preg_replace("/[^A-Za-z0-9 ]/", "", $resourceSvgSlugName)
        ),
        "resourceCustomAuthor1" => $resource["resourceCustomAuthor1"],
        "resourceCustomAuthor2" => $resource["resourceCustomAuthor2"],
      ];
        }

        $output = $regularOutput;
        return $output;
    }

    public function getBlogItems()
    {
        $page = Page::getByID(315);
        $children = $page->getCollectionChildrenArray();
        foreach ($children as $child) {
            $childPage = Page::getByID($child);
            $topics = [];
            $subTopics = [];
            if ($childPage->getAttribute("blog_topic")) {
                $cleanVal = preg_replace(
                    "/[^0-9,.a-zA-Z_]+/",
                    "",
                    $childPage->getAttribute("blog_topic")
                );
                $cleanVal = str_replace("quot", "", $cleanVal);
                $finalVal = explode(",", $cleanVal);
                foreach ($finalVal as $topicFind) {
                    if (str_contains($topicFind, "subtopic_")) {
                        array_push($subTopics, str_replace("subtopic_", "", $topicFind));
                    } elseif (str_contains($topicFind, "topic_")) {
                        array_push($topics, str_replace("topic_", "", $topicFind));
                    }
                }
            }
            $topicsString = implode(",", $topics);
            $subTopicsString = implode(",", $subTopics);
            $output[] = [
        "id" => $child,
        "resourceSlug" => $childPage->getCollectionPath(),
        "resourceType" => 7,
        "resourceTopics" => $topicsString,
        "resourceSubTopics" => $subTopicsString,
        "typeName" => "Blog",
        "resourceName" => $childPage->getCollectionName(),
        "resourceDate" => strtotime($childPage->getCollectionDatePublic()),
      ];
        }
        return $output;
    }

    public function truncateText($string, $your_desired_width)
    {
        $parts = preg_split(
            '/([\s\n\r]+)/',
            $string,
            null,
            PREG_SPLIT_DELIM_CAPTURE
        );
        $parts_count = count($parts);

        $endMarker = strlen($string) > $your_desired_width ? "..." : null;

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $your_desired_width) {
                break;
            }
        }

        return implode(array_slice($parts, 0, $last_part)) . $endMarker;
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

    public function getResourceMeta($resourceId)
    {
        $db = \Database::connection();
        $sql =
      "SELECT resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceDescription, resourcelibrary_resource.id, resourcelibrary_resource.resourceSeoTitle, resourcelibrary_resource.resourceSeoDescription, resourcelibrary_resource.resourceCanonicalUrl, resourcelibrary_resource.resourceGated, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_types.typeName, resourcelibrary_assets.fileName FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_assets ON resourcelibrary_assets.id = resourcelibrary_resource.resourceAsset WHERE resourcelibrary_resource.id = " .
      $resourceId .
      "";
        $this->set("resources", []);
        $returnItem = $db->fetchAssoc($sql);
        if ($returnItem["fileName"] == null) {
            $returnItem["fileName"] = "/packages/company/themes/company/img/logo.png";
        }
        return $returnItem;
    }
}
