<?php

Route::register("/resources/get", function () {
  $originUrls = ["http://www.insightscenter.site"];
  if (in_array($_SERVER["HTTP_ORIGIN"], $originUrls)) {
    $db = Loader::db();
    $resourcesQuery = $db->fetchAll(
      "SELECT * FROM resourcelibrary_resource",
      []
    );
    return json_encode($resourcesQuery);
  } else {
    return false;
  }
});

Route::register("/resources/find", function () {
  $originUrls = ["http://www.insightscenter.site"];
  if (in_array($_SERVER["HTTP_ORIGIN"], $originUrls)) {
    $_POST = json_decode(file_get_contents("php://input"), true);
    $db = \Database::connection();
    $whereStatement = "WHERE";
    $mostRecentDate = "2021-01-01 00:00:00";
    $extraQuery = [
      "resourcelibrary_resource.resourceActive = 1",
      "resourcelibrary_resource.resourceCreated > '" . $mostRecentDate . "'",
    ];
    $extraQueryString = null;
    $offsetCount = $_POST["offset"];
    $searchByQuery = false;
    $queryVal = false;
    $individualWordSearch = false;
    if (
      isset($_POST["query"]) &&
      $_POST["query"] !== "" &&
      strlen($_POST["query"]) > 0
    ) {
      $searchByQuery = true;
      $individualWordSearch = false;
      $queryVal = strtolower($_POST["query"]);
      if (preg_match('/"/', $_POST["query"])) {
        preg_match(
          "/(?:(?:\"(?:\\\\\"|[^\"])+\")|(?:'(?:\\\'|[^'])+'))/is",
          $_POST["query"],
          $match
        );
        $thisString = str_replace('"', "", $match[0]);
        if (strlen($_POST["query"]) >= 3) {
          array_push(
            $extraQuery,
            '(resourcelibrary_resource.resourceName LIKE "%' .
              $thisString .
              '%" OR resourcelibrary_resource.resourceDescription LIKE "%' .
              $thisString .
              '%" OR resourcelibrary_resource.resourceContent LIKE "%' .
              $thisString .
              '%")'
          );
        } else {
          array_push(
            $extraQuery,
            '(resourcelibrary_resource.resourceName LIKE "% ' .
              $thisString .
              ' %" OR resourcelibrary_resource.resourceDescription LIKE "% ' .
              $thisString .
              ' %" OR resourcelibrary_resource.resourceContent LIKE "% ' .
              $thisString .
              ' %")'
          );
        }
      } else {
        $individualWordSearch = true;
        $eachWord = explode(" ", $_POST["query"]);
        $eachItemQuery = [];
        $searchQueryStringKeyword = "";
        foreach ($eachWord as $thisWord) {
          array_push(
            $eachItemQuery,
            '(resourcelibrary_resource.resourceName LIKE "%' .
              $thisWord .
              '%" OR resourcelibrary_resource.resourceDescription LIKE "%' .
              $thisWord .
              '%" OR resourcelibrary_resource.resourceContent LIKE "%' .
              $thisWord .
              '%")'
          );
        }
        $searchQueryStringKeyword = "(" . implode(" OR ", $eachItemQuery) . ")";
        array_push($extraQuery, $searchQueryStringKeyword);
      }
    }
    if (isset($_POST["type"]) && intval($_POST["type"]) !== 0) {
      array_push(
        $extraQuery,
        'resourcelibrary_resource.resourceType = "' . $_POST["type"] . '"'
      );
    }
    if (isset($_POST["topic"]) && intval($_POST["topic"]) !== 0) {
      array_push(
        $extraQuery,
        'CONCAT(",",resourcelibrary_resource.resourceTopics,",") LIKE "%,' .
          $_POST["topic"] .
          ',%"'
      );
    }
    if (isset($_POST["area"]) && intval($_POST["area"]) !== 0) {
      array_push(
        $extraQuery,
        'CONCAT(",",resourcelibrary_resource.resourceAreas,",") LIKE "%,' .
          $_POST["area"] .
          ',%"'
      );
    }
    $featuredArray = [];
    //GETTING FEATURED ITEMS IF NO FILTERS ARE SELECTED SO WE PREVENT THEM FROM BEING SEEN
    if (
      isset($_POST["type"]) &&
      intval($_POST["type"]) == 0 &&
      isset($_POST["topic"]) &&
      intval($_POST["topic"]) == 0 &&
      isset($_POST["area"]) &&
      intval($_POST["area"]) == 0 &&
      isset($_POST["query"]) &&
      ($_POST["query"] == "" || strlen($_POST["query"]) <= 0)
    ) {
      $featuredResourcesQuery = $db->fetchAssoc(
        "SELECT * FROM resourcelibrary_featured WHERE id = 1",
        []
      );
      for ($x = 1; $x <= 12; $x++) {
        $item = unserialize($featuredResourcesQuery["resourceFeatured" . $x]);
        $itemId = $item["id"];
        array_push($featuredArray, $itemId);
      }
    }
    if (count($featuredArray) > 0) {
      $featuredArrayString = implode(",", $featuredArray);
      array_push(
        $extraQuery,
        "resourcelibrary_resource.id NOT IN (" . $featuredArrayString . ")"
      );
    }
    for ($x = 0; $x < count($extraQuery); $x++) {
      $whereStatement = $x == 0 ? "WHERE" : " AND";
      $extraQueryString .= $whereStatement . " " . $extraQuery[$x];
    }
    $sqlCount =
      "SELECT COUNT(*) as num FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail " .
      $extraQueryString .
      " ORDER BY resourcelibrary_resource.resourceIsFeatured DESC;";
    $returnItemCount = $db->fetchAll($sqlCount);
    foreach ($returnItemCount as $returnItemCounter) {
      $currentPageTest = 9 + ($offsetCount + 1);
      $numItems = $returnItemCounter["num"];
      if ($numItems > $currentPageTest) {
        $canAddPage = true;
      } else {
        $canAddPage = false;
      }
    }
    $totalMax = $returnItemCounter["num"];
    $offsetCount = $_POST["offset"];
    if (!isset($offsetCount)) {
      $offsetCount = 0;
    }
    $maxLimit = isset($_POST["count"]) ? $_POST["count"] : 9;
    $sql =
      "SELECT resourcelibrary_resource.resourceName, resourcelibrary_resource.resourceThumbnail, resourcelibrary_resource.resourceCid, resourcelibrary_resource.id AS resourceId, resourcelibrary_resource.resourceSlug, resourcelibrary_resource.resourceGated, resourcelibrary_resource.resourceDescription AS resourceDescription, resourcelibrary_resource.resourceContent, resourcelibrary_resource.resourceType, resourcelibrary_resource.resourceAsset, resourcelibrary_resource.resourcePageType, resourcelibrary_resource.resourceUrl, resourcelibrary_resource.resourceCustomAuthor1, resourcelibrary_resource.resourceTopics, resourcelibrary_resource.resourceSubTopics, resourcelibrary_resource.resourceCreated, resourcelibrary_types.typeName, resourcelibrary_thumbnails.fileName AS thumbnailFile FROM resourcelibrary_resource LEFT JOIN resourcelibrary_types ON resourcelibrary_types.id = resourcelibrary_resource.resourceType LEFT JOIN resourcelibrary_thumbnails ON resourcelibrary_thumbnails.id = resourcelibrary_resource.resourceThumbnail " .
      $extraQueryString .
      " ORDER BY resourcelibrary_resource.resourceCreated DESC;";
    $returnItem = $db->fetchAll($sql);
    foreach ($returnItem as $resource) {
      $canAdd = true;
      if ($resource["resourceCid"]) {
        $checkForActive = Page::getByID($resource["resourceCid"], "ACTIVE");
        if (!$checkForActive->cIsActive || $checkForActive->cIsActive == 0) {
          $canAdd = false;
        }
      }
      if (
        $resource["resourceThumbnail"] == null ||
        $resource["resourceThumbnail"] == 0
      ) {
        if ($resource["resourceType"] == 2) {
          $thumbUrl = "/img/default1.png";
        } elseif ($resource["resourceType"] == 3) {
          $thumbUrl = "/img/default1.png";
        } elseif ($resource["resourceType"] == 1) {
          $thumbUrl = "/img/Blog.png";
        } else {
          $thumbUrl = "/img/default1.png";
        }
      } else {
        $thumbUrl =
          "/application/files/resources/thumbnails/" .
          str_replace(" ", "%20", $resource["thumbnailFile"]);
      }
      if (
        $resource["resourceUrl"] &&
        $resource["resourceUrl"] !== "" &&
        $resource["resourcePageType"] == 2
      ) {
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
      $searchScore = 0;
      if ($queryVal) {
        if ($individualWordSearch) {
          $eachWord = explode(" ", $queryVal);
          foreach ($eachWord as $thisWord) {
            $searchScore =
              $searchScore + intval(getSearchScore($resource, $thisWord));
          }
        } else {
          $searchScore = intval(getSearchScore($resource, $queryVal));
        }
      }
      $output[] = [
        "totalChecker" => $totalMax,
        "counter" => $canAddPage,
        "id" => $resource["resourceId"],
        "name" => $resource["resourceName"],
        "typeName" => $resource["typeName"],
        "slug" => $resource["resourceSlug"],
        "url" => $resourceUrl,
        "target" => $linkTarget,
        "thumbnail" => $thumbUrl,
        "isGated" => $resource["resourceGated"],
        "resourceCustomAuthor1" => $resource["resourceCustomAuthor1"],
        "resourceDate" => date(
          "M j, Y",
          strtotime($resource["resourceCreated"])
        ),
        "searchScore" => $searchScore,
      ];
    }
    $thisOutput = [];
    if ($searchByQuery && isset($output)) {
      usort($output, function ($a, $b) {
        return $a["searchScore"] <=> $b["searchScore"];
      });
      $output = array_reverse($output);
    }
    if (isset($output)) {
      $thisOutput = array_slice(
        $output,
        $offsetCount,
        $offsetCount + $maxLimit
      );
    }
    return json_encode($thisOutput);
  } else {
    return false;
  }
});

function getSearchScore($resource, $queryVal)
{
  $searchScore = 0;
  if (strtolower($resource["resourceName"]) == $queryVal) {
    $searchScore += 100;
  }
  if (
    strpos(strtolower($resource["resourceName"]), $queryVal . " ") !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 25 * $substringCount;
  }
  if (
    strpos(strtolower($resource["resourceName"]), " " . $queryVal) !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 25 * $substringCount;
  }
  if (
    strpos(strtolower($resource["resourceName"]), $queryVal . ")") !== false ||
    strpos(strtolower($resource["resourceName"]), "(" . $queryVal) !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 25 * $substringCount;
  }
  if (
    strpos(strtolower($resource["resourceName"]), $queryVal . "-") !== false ||
    strpos(strtolower($resource["resourceName"]), "-" . $queryVal) !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 25 * $substringCount;
  }
  if (
    strpos(strtolower($resource["resourceName"]), $queryVal . '"') !== false ||
    strpos(strtolower($resource["resourceName"]), '"' . $queryVal) !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 25 * $substringCount;
  }
  if (strpos(strtolower($resource["resourceName"]), $queryVal) !== false) {
    $substringCount = substr_count(
      strtolower($resource["resourceName"]),
      $queryVal
    );
    $searchScore += 10 * $substringCount;
  }
  if (
    strpos(strtolower($resource["resourceDescription"]), $queryVal) !== false
  ) {
    $substringCount = substr_count(
      strtolower($resource["resourceDescription"]),
      $queryVal
    );
    $searchScore += 3 * $substringCount;
  }
  if (strpos(strtolower($resource["resourceSlug"]), $queryVal) !== false) {
    $substringCount = substr_count(
      strtolower($resource["resourceSlug"]),
      $queryVal
    );
    $searchScore += 1 * $substringCount;
  }
  return $searchScore;
}
