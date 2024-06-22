<?php $this->inc("elements/header.php");
$page = Page::getCurrentPage();
$pageSlug = $c->getCollectionPath();
if (substr($pageSlug, -1) !== "/") {
    $pageSlug = $pageSlug . "/";
}
$isLoggedIn = User::isLoggedIn();
?>

<div class="page-template-full">
    <div class="contents">
        <?php if (!$resourceview) {
    echo '<div class="resources_hero">';
    $this->inc("elements/hero.php");
    echo "</div>";
} else {
    echo '<div class="row resource_hero"><div id="hero" class="third regular"><div class="container left"><div class="content"><h1>' .
    $thisResource["resourceName"] .
    "</h1></div></div></div></div>";
} ?>
        <?php $this->inc("elements/subhero.php"); ?>
        <div class="row first">
            <div class="container resources_container">
                <?php if (!$resourceview) { ?>
                <ul id="insight_filters">
                    <li>
                        <form id="resource_search">
                            <input type="text" placeholder="What are you looking for?">
                            <button type="submit" class="submit"><i class="far fa-search" aria-hidden="true"></i></button>
                        </form>
                    </li>
                    <li class="select">
                        <select name="type" id="type">
                            <option value="0">Content Type</option>
                            <?php foreach ($types as $type) {
    echo '<option value="' .
                        $type["id"] .
                        '">' .
                        $type["typeName"] .
                        "</option>";
} ?>
                        </select>
                    </li>
                    <li class="select">
                        <select name="topic" id="topic">
                            <option value="0">Topic</option>
                        </select>
                    </li>
                </ul>
                <div id="resources_list">
                    <?php foreach ($resources as $key => $resource) {
    $activeCheck = $key < 8 ? "active" : null;
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
    echo '<a href="' .
            $resourceUrl .
            '" target="' .
            $linkTarget .
            '" class="resource ' .
            $activeCheck .
            '" data-id="' .
            $resource["id"] .
            '">';
    if ($resource["resourceThumbnail"]) {
        echo '<div class="thumbnailimg" style="background-image: url(/application/files/resources/thumbnails/' .
              $resource["resourceThumbnail"] .
              ')"><div class="left"></div><div class="right"></div></div>';
    } elseif ($resourcePage && $resourcePage->getAttribute("thumbnail")) {
        $thumbnail = $resourcePage->getAttribute("thumbnail");
        if (is_object($thumbnail)) {
            $thumburl = $thumbnail->getRelativePath();
        }
        echo '<div class="thumbnailimg" style="background-image: url(' .
              $thumburl .
              ')"><div class="left"></div><div class="right"></div></div>';
    } else {
        if ($resource["resourceType"] == 2) {
            $thumburl = "/img/default1.png";
        } elseif ($resource["resourceType"] == 3) {
            $thumburl = "/img/default2.png";
        } elseif ($resource["resourceType"] == 1) {
            $thumburl = "/img/default3.png";
        } else {
            $thumburl = "/img/default4.png";
        }
        echo '<div class="thumbnailimg icon" style="background-image: url(' .
              $thumburl .
              ')"><div class="left"></div><div class="right"></div></div>';
    }
    echo '<div class="resource_content">';
    echo '<p class="type">' . $resource["typeName"] . "</p>";
    echo '<p class="title">' .
            $controller->truncateText($resource["resourceName"], 75) .
            "</p>";
    echo "</div>";
    echo '<div class="resource_hover">';
    echo '<div class="resource_content">';
    if ($resource["resourceDescription"]) {
        echo '<p class="description">' .
              $controller->truncateText($resource["resourceDescription"], 250) .
              "</p>";
    } elseif (
            $resourcePage &&
            $resourcePage->getCollectionDescription()
          ) {
        echo '<p class="description">' .
              $controller->truncateText(
                  $resourcePage->getCollectionDescription(),
                  250
              ) .
              "</p>";
    }
    echo "</div>";
    echo "</div>";
    echo "</a>";
} ?>
                </div>
                <?php if (count($resources) >= 8) {
    echo '<p class="loadmore btn raspberry"><a>Load more</a></p>';
} ?>
                <?php } else {
    echo '<div class="resource_item">';
    if ($isLoggedIn) {
        echo '<p class="btn raspberry"><a href="/index.php/dashboard/resource_library/resources/edit/' .
              $thisResource["id"] .
              '">Edit Resource</a></p>';
        echo "<br><br>";
    }
    echo '<div class="resources_container">';
    echo $thisResource["resourceContent"];
    if ($thisResource["fileName"] && $thisResource["fileName"] !== "") {
        if ($thisResource["resourcePageType"] == 1) {
            echo '<br><p class="btn raspberry"><a target="_blank" href="/application/files/resources/' .
                $thisResource["fileName"] .
                '">DOWNLOAD RESOURCE</a></p>';
        } else {
            echo '<object data="/application/files/resources/assets/' .
                $thisResource["fileName"] .
                '" width="100%" height="750"> </object>';
        }
    }
    if ($thisResource["resourceType"] == 3) {
        $parsedVideoUrl = parse_url($thisResource["resourceMediaLink"]);
        echo '<div class="videoholder">';
        if (
              str_contains($parsedVideoUrl["host"], "youtube") ||
              str_contains($parsedVideoUrl["host"], "youtu.be")
            ) {
            if (
                $parsedVideoUrl["host"] == "www.youtube.com" ||
                $parsedVideoUrl["host"] == "youtube.com"
              ) {
                $videoIdParts = explode(
                    "v=",
                    $thisResource["resourceMediaLink"]
                );
                $videoId = $videoIdParts[1];
            }
            if ($parsedVideoUrl["host"] == "youtu.be") {
                $videoId = str_replace("/", "", $parsedVideoUrl["path"]);
            }
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' .
                $videoId .
                '" title="' .
                $resource["resourceName"] .
                '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        if (str_contains($parsedVideoUrl["host"], "vimeo")) {
            $videoId = str_replace("/", "", $parsedVideoUrl["path"]);
            echo '<iframe src="https://player.vimeo.com/video/' .
                $videoId .
                '" width="640" height="367" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        }
        echo "</div>";
    }
    echo "</div></div>";
} ?>
                // print_r($thisResource);
            </div>
        </div>
    </div>
</div>

<?php $this->inc("elements/footer.php"); ?>

<script>
if ($("#resources_list")[0]) {
    var $listgrid = $("#resources_list").isotope({
        itemSelector: ".resource.active",
        percentPosition: true,
        layoutMode: 'fitRows',
    });
}

function performFilter() {
    searchQuery = $("form#resource_search input[type='text']").val();
    contentTypeSearch = $("#insight_filters select#type").val();
    topicSearch = $("#insight_filters select#topic").val();
    if (searchQuery == "" && contentTypeSearch == 0 && topicSearch == 0) {
        for (x = 0; x < 8; x++) {
            $("#resources_list .resource").eq(x).addClass("active");
        }
        $listgrid.isotope({
            filter: ".resource.active"
        });
        $("p.loadmore").show();
    } else {
        $("#resources_list").prepend('<h3 class="loadingtext">Loading results...</h3>');
        $("#resources_list .resource").removeClass("active");
        $("p.loadmore").hide();
        searchObj = {
            query: searchQuery,
            type: contentTypeSearch,
            topic: topicSearch
        };
        $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '/resources/find',
                data: searchObj, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true
            })
            .done(function(data) {
                $("#resources_list h3.loadingtext").remove();
                // console.log(data); 
                data.forEach(data => {
                    Object.entries(data).forEach(([key, value]) => {
                        if (key == "id") {
                            $("#resources_list a.resource[data-id='" + value + "']").addClass("active");
                        }
                    });
                });
                $('#resources_list').isotope('reloadItems').isotope();
            });
    }
}
$("form#resource_search").submit(function(e) {
    e.preventDefault();
    performFilter();
});
$('#insight_filters select').on('change', function() {
    performFilter();
});
</script>