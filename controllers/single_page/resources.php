<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage;

use PageController;
use Database;
use Core;
use Loader;
use Page;
use Request;

class Resources extends PageController
{
    public function on_start()
    {
        $this->addFooterItem(
            '<script type="text/javascript" src="/packages/resource_library/js/isotope.pkgd.min.js"></script>'
        );
        $this->addFooterItem(
            '<script type="text/javascript" src="/packages/resource_library/js/resources.js"></script>'
        );
        $this->addHeaderItem(
            '<link rel="stylesheet" href="/packages/resource_library/styles/resources.css">'
        );
    }

    public function view($resourcePath = null, $resourceSlug = null)
    {
        if ($resourceSlug == null) {
            $db = \Database::connection();
            $this->requireAsset("javascript", "jquery");
            $this->requireAsset("css", "font-awesome");
            $this->set("title", "Resources");
            $allResources = $this->getResourceItems();
            // usort($allResources, function($a, $b) {
            //     return $a['resourceDate'] <=> $b['resourceDate'];
            // });
            $this->set("resources", $allResources);
            $sql = "SELECT * from resourcelibrary_types ORDER BY typeName ASC;";
            $returnItem = $db->fetchAll($sql);
            $this->set("types", $returnItem);
            $sql = "SELECT * from resourcelibrary_topics ORDER BY topicName ASC;";
            $returnItem = $db->fetchAll($sql);
            $returnItem = $db->fetchAll($sql);
            $this->set("topics", $returnItem);
            $sql =
        "SELECT * from resourcelibrary_subtopics ORDER BY subTopicName ASC;";
            $returnItem = $db->fetchAll($sql);
            $returnItem = $db->fetchAll($sql);
            $this->set("resourceview", false);
            $this->set("subtopics", $returnItem);
        } else {
            $this->set("resourceview", true);
            $db = \Database::connection();
            $sql =
        "SELECT resourcelibrary_resource.id, resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceSeoTitle, resourcelibrary_resource.resourceSeoDescription, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_types.typeName, resourcelibrary_assets.fileName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_assets ON resourcelibrary_assets.id = resourcelibrary_resource.resourceAsset LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceSlug = '" .
        $resourceSlug .
        "'";
            $returnItem = $db->fetchAssoc($sql);
            $this->set("thisResource", $returnItem);
            $this->set("title", $returnItem["resourceName"]);
            $seoName =
        $returnItem["resourceSeoTitle"] &&
        strlen($returnItem["resourceSeoTitle"]) > 0
          ? $returnItem["resourceSeoTitle"]
          : $returnItem["resourceName"];
            $this->set("customPageTitle", $seoName . " | Insights Center");
            $this->set("resourceDescription", $resourceSeoDescription);
            $thumbUrl = $returnItem["thumbnailFile"]
        ? "/application/files/resources/thumbnails/" .
          $returnItem["thumbnailFile"]
        : "/img/logo.png";
            $this->set("resourceThumbnail", $thumbUrl);
        }
    }

    public function getResourceItems()
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
        $sql =
      "SELECT resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceIsFeatured, resourcelibrary_resource.resourceCid, resourcelibrary_resource.resourceMediaLink, resourcelibrary_resource.id AS resourceId, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourceDescription AS resourceDescription, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCreated, resourcelibrary_types.typeName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail WHERE resourcelibrary_resource.resourceCreated > '" .
      $mostRecentDate .
      "' ORDER BY resourcelibrary_resource.resourceCreated DESC;";
        $returnItem = $db->fetchAll($sql);
        foreach ($returnItem as $resource) {
            if (
        $resource["resourceIsFeatured"] !== 0 &&
        $resource["resourceIsFeatured"] !== "0"
      ) {
                $featuredOutput[] = [
          "id" => $resource["resourceId"],
          "featuredId" => $resource["resourceIsFeatured"],
          "resourceSlug" => $resource["resourceSlug"],
          "resourceType" => $resource["resourceType"],
          "resourceTopics" => $resource["resourceTopics"],
          "resourceSubTopics" => $resource["resourceSubTopics"],
          "typeName" => $resource["typeName"],
          "resourceName" => $resource["resourceName"],
          "resourceThumbnail" => $resource["thumbnailFile"],
          "resourceDate" => strtotime($resource["resourceCreated"]),
          "resourceDescription" => $resource["resourceDescription"],
          "resourceCid" => $resource["resourceCid"],
          "resourceMediaLink" => $resource["resourceMediaLink"],
        ];
            } else {
                $regularOutput[] = [
          "id" => $resource["resourceId"],
          "resourceSlug" => $resource["resourceSlug"],
          "resourceType" => $resource["resourceType"],
          "resourceTopics" => $resource["resourceTopics"],
          "resourceSubTopics" => $resource["resourceSubTopics"],
          "typeName" => $resource["typeName"],
          "resourceName" => $resource["resourceName"],
          "resourceThumbnail" => $resource["thumbnailFile"],
          "resourceDate" => strtotime($resource["resourceCreated"]),
          "resourceDescription" => $resource["resourceDescription"],
          "resourceCid" => $resource["resourceCid"],
          "resourceMediaLink" => $resource["resourceMediaLink"],
        ];
            }
        }

        if (isset($featuredOutput) && count($featuredOutput) > 0) {
            $output = array_merge($featuredOutput, $regularOutput);
        } else {
            $output = $regularOutput;
        }
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
}
