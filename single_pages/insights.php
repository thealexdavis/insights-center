<?php $this->inc("elements/header.php");
$page = Page::getCurrentPage();
$pageSlug = $c->getCollectionPath();
$therapeuticAreas = Page::getByID(232)->getCollectionChildrenArray();
if (substr($pageSlug, -1) !== "/") {
    $pageSlug = $pageSlug . "/";
}
$isLoggedIn = User::isLoggedIn();
function estimateReadingTime($text, $wpm = 200)
{
    $totalWords = str_word_count(strip_tags($text));
    $minutes = floor($totalWords / $wpm);
    $seconds = floor(($totalWords % $wpm) / ($wpm / 60));

    return $minutes;
}
function tokenTruncate($string, $your_desired_width)
{
    //OVERRIDING DESIRED WORD LENGTH
    $your_desired_width = 40;
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
    $parts_count = count($parts);

    $length = 0;
    $last_part = 0;
    for (; $last_part < $parts_count; ++$last_part) {
        $length += strlen($parts[$last_part]);
        if ($length > $your_desired_width) {
            break;
        }
    }

    return implode(array_slice($parts, 0, $last_part)) . "...";
}
?>
<div class="insights_page">
    <div id="main">
        <div class="page-template-full">
            <div class="contents">
                <?php if (!$resourceview) {
    $a = new Area("Hero");
    $a->enableGridContainer();
    $a->display($c);
} ?>
                <div class="row first">
                    <?php if (!$resourceview) {
    $a = new Area("Main");
    $a->enableGridContainer();
    $a->display($c);
} ?>
                    <div class="container resources_container">
                        <?php if (!$resourceview) { ?>
                        <?php if ($pageCrawl <= 1) { ?>
                        <form id="insightfilter">
                            <ul id="insight_filters">
                                <li class="label">
                                    <p>Filter by:</p>
                                </li>
                                <li class="query">
                                    <div class="form-group">
                                        <label for="insight_filter_query">&nbsp;</label>
                                        <input type="text" id="insight_filter_query" name="query" value="<?php if (
                                                                                    isset($_GET["query"])
                                                                                ) {
    echo $_GET["query"];
} ?>" placeholder="Keyword">
                                        <button type="submit" id="filter_form_submit_btn" aria-label="Submit Insights Search"><svg xmlns="http://www.w3.org/2000/svg" width="16.997" height="17" viewBox="0 0 16.997 17">
                                                <path id="resources-search-magnifying-glass-solid" d="M13.81,6.905a6.888,6.888,0,0,1-1.328,4.073l4.2,4.206a1.063,1.063,0,0,1-1.5,1.5l-4.2-4.206A6.906,6.906,0,1,1,13.81,6.905ZM6.905,11.686A4.781,4.781,0,1,0,2.125,6.905,4.782,4.782,0,0,0,6.905,11.686Z" transform="translate(0 0)" fill="#4e565b" />
                                            </svg>
                                        </button>
                                        <input type="submit" id="filter_form_submit_input">
                                    </div>
                                </li>
                                <li class="therapeutic_areas">
                                    <div class="form-group half first">
                                        <label for="resourceTherapeuticAreas">Therapeutic areas</label>
                                        <select class="select" id="resourceTherapeuticAreas">
                                            <option value="0">All areas</option>
                                            <?php foreach (
                                                                                            $therapeuticAreas
                                                                                            as $key => $area
                                                                                        ) {
    if (
                                                                                                isset($_GET["area"]) &&
                                                                                                $_GET["area"] == $area
                                                                                            ) {
        $selectedCheck = "selected";
    } else {
        $selectedCheck = "";
    }
    echo '<option value="' .
                                                                                                $area .
                                                                                                '" ' .
                                                                                                $selectedCheck .
                                                                                                ">" .
                                                                                                Page::getByID(
                                                                                                    $area
                                                                                                )->getCollectionName() .
                                                                                                "</option>";
} ?>
                                        </select>
                                    </div>
                                </li>
                                <li class="topic">
                                    <div class="form-group half first">
                                        <label for="resourceFilterTopic">Popular topics</label>
                                        <select class="select" id="resourceFilterTopic">
                                            <option value="0">All topics</option>
                                            <?php foreach ($topics as $topic) {
    if (
                                                                                                isset($_GET["topic"]) &&
                                                                                                $_GET["topic"] == $topic["id"]
                                                                                            ) {
        $selectedCheck = "selected";
    } else {
        $selectedCheck = "";
    }
    echo '<option value="' .
                                                                                                $topic["id"] .
                                                                                                '" ' .
                                                                                                $selectedCheck .
                                                                                                ">" .
                                                                                                $topic["topicName"] .
                                                                                                "</option>";
} ?>
                                        </select>
                                    </div>
                                </li>
                                <li class="type">
                                    <div class="form-group half first">
                                        <label for="resourceFilterType">Insight type</label>
                                        <select class="select" id="resourceFilterType">
                                            <option value="0">All types</option>
                                            <?php foreach ($types as $type) {
    if (
                                                                                                isset($_GET["type"]) &&
                                                                                                $_GET["type"] == $type["id"]
                                                                                            ) {
        $selectedCheck = "selected";
    } else {
        $selectedCheck = "";
    }
    echo '<option value="' .
                                                                                                $type["id"] .
                                                                                                '" ' .
                                                                                                $selectedCheck .
                                                                                                ">" .
                                                                                                $type["typeName"] .
                                                                                                "</option>";
} ?>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </form>
                        <?php } ?>
                        <div id="resources_list">
                            <div class="container" id="insert_resources_row">
                                <?php if ($pageCrawl <= 1) { ?>
                                <div class="row">
                                    <div class="col col-sm-50 col-md-33 col-offset-2">
                                        <a href="<?php echo $featuredResources[0][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[0][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[0]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[0][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[0][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[0][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[0][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[0][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[0][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[0][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[0][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[0][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col col-sm-50 col-md-15">
                                        <a href="<?php echo $featuredResources[1][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[1][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[1]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[1][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[1][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[1][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[1][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[1][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[1][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[1][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[1][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[1][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm-50 col-md-15 col-offset-2">
                                        <a href="<?php echo $featuredResources[2][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[2][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[2]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[2][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[2][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[2][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[2][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[2][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[2][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[2][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[2][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[2][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col col-sm-50 col-md-16 col-offset-2">
                                        <a href="<?php echo $featuredResources[3][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[3][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[3]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[3][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[3][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[3][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[3][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[3][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[3][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[3][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[3][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[3][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col col-sm-50 col-md-15">
                                        <a href="<?php echo $featuredResources[4][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-1 <?php echo $featuredResources[4][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[4]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[4][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[4][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[4][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo tokenTruncate(
                                                                                                    $featuredResources[4][
                                                                                                        "resourceName"
                                                                                                    ],
                                                                                                    60
                                                                                                ); ?></p>
                                                <?php if (
                                                                                                    $featuredResources[4][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[4][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[4][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[4][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[4][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                            </div>
                                        </a>
                                        <a href="<?php echo $featuredResources[5][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-1 nobtm <?php echo $featuredResources[5][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[5]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[5][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[5][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[5][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo tokenTruncate(
                                                                                                    $featuredResources[5][
                                                                                                        "resourceName"
                                                                                                    ],
                                                                                                    60
                                                                                                ); ?></p>
                                                <?php if (
                                                                                                    $featuredResources[5][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[5][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[5][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[5][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[5][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm-50 col-md-15 col-offset-2">
                                        <a href="<?php echo $featuredResources[6][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[6][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[6]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[6][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[6][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[6][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[6][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[6][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[6][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[6][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[6][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[6][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col col-sm-50 col-md-33">
                                        <a href="<?php echo $featuredResources[7][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[7][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[7]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[7][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[7][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[7][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[7][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[7][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[7][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[7][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[7][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[7][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-sm-50 col-md-15 col-offset-2">
                                        <a href="<?php echo $featuredResources[8][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-1 <?php echo $featuredResources[8][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[8]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[8][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[8][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[8][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo tokenTruncate(
                                                                                                    $featuredResources[8][
                                                                                                        "resourceName"
                                                                                                    ],
                                                                                                    60
                                                                                                ); ?></p>
                                                <?php if (
                                                                                                    $featuredResources[8][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[8][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[8][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[8][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[8][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                            </div>
                                        </a>
                                        <a href="<?php echo $featuredResources[9][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-1 nobtm <?php echo $featuredResources[9][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[9]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[9][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[9][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[9][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo tokenTruncate(
                                                                                                    $featuredResources[9][
                                                                                                        "resourceName"
                                                                                                    ],
                                                                                                    60
                                                                                                ); ?></p>
                                                <?php if (
                                                                                                    $featuredResources[9][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[9][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[9][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[9][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[9][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                            </div>
                                        </a>

                                    </div>
                                    <div class="col col-sm-50 col-md-16 col-offset-2">
                                        <a href="<?php echo $featuredResources[10][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[10][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[10]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[10][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[10][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[10][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[10][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[10][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[10][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[10][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[10][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[10][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col col-sm-50 col-md-15">
                                        <a href="<?php echo $featuredResources[11][
                                                                                    "resourceUrl"
                                                                                ]; ?>" class="insight_block initial_insight height-2 <?php echo $featuredResources[11][
    "thumbnailClasses"
]; ?>"><?php if ($featuredResources[11]["isGated"]) {
    echo '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
}; ?>
                                            <div class="container">
                                                <p class="type"><?php echo ${$featuredResources[11][
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $featuredResources[11][
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $featuredResources[11][
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $featuredResources[11][
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                                <?php if (
                                                                                                    $featuredResources[11][
                                                                                                        "resourceExpert"
                                                                                                    ] ||
                                                                                                    $featuredResources[11][
                                                                                                        "resourceCustomAuthor1"
                                                                                                    ]
                                                                                                ) {
                                                                                                    $unserializedAuthor = isset(
                                                                                                        $featuredResources[11][
                                                                                                            "resourceCustomAuthor1"
                                                                                                        ]
                                                                                                    )
                                                                                                        ? unserialize(
                                                                                                            $featuredResources[11][
                                                                                                                "resourceCustomAuthor1"
                                                                                                            ]
                                                                                                        )
                                                                                                        : false;
                                                                                                    if (
                                                                                                        $unserializedAuthor &&
                                                                                                        $unserializedAuthor[
                                                                                                            "byline"
                                                                                                        ] > 0
                                                                                                    ) {
                                                                                                        $authorByLine =
                                                                                                            $unserializedAuthor[
                                                                                                                "byline"
                                                                                                            ];
                                                                                                        $expertThumbnail =
                                                                                                            $unserializedAuthor[
                                                                                                                "thumb"
                                                                                                            ];
                                                                                                    } else {
                                                                                                        // unserialize($resourcesQuery['resourceCustomAuthor1'])
                                                                                                        $authorPage = Page::getByID(
                                                                                                            $featuredResources[11][
                                                                                                                "resourceExpert"
                                                                                                            ]
                                                                                                        );
                                                                                                        $authorName = $authorPage->getCollectionName();
                                                                                                        $expertThumbnail = $authorPage->getAttribute(
                                                                                                            "expert_headshot"
                                                                                                        )
                                                                                                            ? $authorPage
                                                                                                                ->getAttribute(
                                                                                                                    "expert_headshot"
                                                                                                                )
                                                                                                                ->getDownloadURL()
                                                                                                            : "";
                                                                                                        $authorTitle = $authorPage->getAttribute(
                                                                                                            "title"
                                                                                                        );
                                                                                                        $authorByLine =
                                                                                                            $authorName .
                                                                                                            ", " .
                                                                                                            $authorTitle;
                                                                                                    } ?>
                                                <?php if (
                                                                                                    strlen(
                                                                                                        str_replace(
                                                                                                            ", ",
                                                                                                            "",
                                                                                                            $authorByLine
                                                                                                        )
                                                                                                    ) > 0
                                                                                                ) { ?>
                                                <p class="author">
                                                    <span class="thumb" style="background-image: url(<?php echo $expertThumbnail; ?>)"></span>
                                                    <span class="authorinfo">By <?php echo $authorByLine; ?></span>
                                                </p>
                                                <?php } ?>
                                                <?php
                                                                                                } ?>
                                                <p class="info"></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="row">
                                    <?php
                                                                        $itemCount = 1;
                                                                        foreach ($resources as $resource) {
                                                                            if ($itemCount < 3) {
                                                                                $offsetCount = "col-offset-2";
                                                                                $itemCount++;
                                                                            } else {
                                                                                $offsetCount = "";
                                                                                $itemCount = 1;
                                                                            } ?>
                                    <div class="col col-sm-50 col-md-15 <?php echo $offsetCount; ?>"><a href="<?php echo $resource[
    "resourceUrl"
]; ?>" class="insight_block added_insight height-2 white none">
                                            <div class="container">
                                                <p class="type"><?php echo ${$resource[
                                                                                                    "iconName"
                                                                                                ] .
                                                                                                    "_icon"}; ?> <?php echo $resource[
     "typeName"
 ]; ?></p>
                                                <div class="img" style="background-image: url(<?php echo $resource[
                                                                                                    "resourceThumbnail"
                                                                                                ]; ?>);"></div>
                                                <p class="title"><?php echo $resource[
                                                                                                    "resourceName"
                                                                                                ]; ?></p>
                                            </div>
                                        </a></div>
                                    <?php
                                                                        }
                                                                        ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if (count($resources) >= 8) {
                                                                            echo '<a class="resource-pagelink" href="/insights/page/' .
                                                        $pageCrawl .
                                                        '" tabindex="-1">Next Page</a>';
                                                                            if ($pageCrawl > 1) {
                                                                                echo '<br><div class="btn_container"><p style="text-align: center;"><a href="/insights" class="btn">Back to Insights Home</a></p></div>';
                                                                            } else {
                                                                                echo '<div class="load_more_holder"><p class="loadmore"><a class="btn ghost" id="load_more_resources_btn">Load more</a></p></div>';
                                                                            }
                                                                        } ?>
                        <?php } else {
                                                                            echo '<div class="insights_top">';
                                                                            echo '<nav class="breadcrumbs" role="navigation" aria-label="breadcrumb"><ol class="breadcrumb"><li><a href="/" target="_self">Home</a></li><li><a href="/insights" target="_self">Insights</a></li>';
                                                                            echo '<li><a href="/insights?type=' .
                                                        $thisResource["resourceType"] .
                                                        '" target="_self">' .
                                                        $thisResource["typeName"] .
                                                        "</a></li>";
                                                                            echo '<li class="active">' .
                                                        $thisResource["resourceName"] .
                                                        "</li>";
                                                                            echo "</ol></nav>";
                                                                            echo "</div>";
                                                                            echo '<div class="resource_item">';
                                                                            if ($isLoggedIn) {
                                                                                echo '<p class="btn raspberry"><a href="/index.php/dashboard/resource_library/resources/edit/' .
                                                            $thisResource["id"] .
                                                            '">Edit Resource</a></p>';
                                                                                echo "<br><br>";
                                                                            }
                                                                            $resourceExperts = $thisResource["resourceExperts"];
                                                                            $resourceExpertsItem = explode(",", $resourceExperts);
                                                                            echo '<div class="resources_container">';
                                                                            echo "<h1>" . $thisResource["resourceName"] . "</h1>";
                                                                            // if ($thisResource['resourceType'] == 1) {
                                                                            if (count($resourceExpertsItem) > 0) {
                                                                                echo '<div class="author_area">';
                                                                                echo '<p class="info">';
                                                                                if ($thisResource["resourceType"] == 1) {
                                                                                    echo ' <svg xmlns="http://www.w3.org/2000/svg" width="10.855" height="10.855" viewBox="0 0 10.855 10.855"><path id="clock-regular" d="M4.919,2.544a.509.509,0,0,1,1.018,0V5.156l1.809,1.2a.491.491,0,0,1,.123.706.467.467,0,0,1-.687.123L5.146,5.833a.465.465,0,0,1-.227-.424ZM5.428,0A5.428,5.428,0,1,1,0,5.428,5.427,5.427,0,0,1,5.428,0ZM1.018,5.428a4.41,4.41,0,1,0,4.41-4.41A4.409,4.409,0,0,0,1.018,5.428Z" fill="#a7aaad"/></svg> ' .
                                                                estimateReadingTime(
                                                                    $thisResource["resourceContent"]
                                                                ) .
                                                                " min";
                                                                                }
                                                                                echo "</p>";
                                                                                for ($z = 1; $z <= 2; $z++) {
                                                                                    $customAuthorItem = unserialize(
                                                                                        $thisResource["resourceCustomAuthor" . $z]
                                                                                    );
                                                                                    if (
                                                                isset($customAuthorItem["byline"]) &&
                                                                strlen($customAuthorItem["byline"]) > 0
                                                            ) {
                                                                                        $hyperlinkStart = "";
                                                                                        $hyperlinkEnd = "";
                                                                                        if (
                                                                    $customAuthorItem["url"] &&
                                                                    strlen($customAuthorItem["url"]) > 0
                                                                ) {
                                                                                            $hyperlinkStart =
                                                                        "<a href='" .
                                                                        $customAuthorItem["url"] .
                                                                        "' target='_blank'>";
                                                                                            $hyperlinkEnd = "</a>";
                                                                                        }
                                                                                        echo "<p>" .
                                                                    $hyperlinkStart .
                                                                    "By " .
                                                                    $customAuthorItem["byline"] .
                                                                    "" .
                                                                    $hyperlinkEnd .
                                                                    "</p>";
                                                                                    }
                                                                                }
                                                                                foreach ($resourceExpertsItem as $expertId) {
                                                                                    if ($expertId && $expertId > 0) {
                                                                                        $expertPage = Page::getByID($expertId);
                                                                                        if ($expertPage) {
                                                                                            echo '<p><a href="' .
                                                                                $expertPage->getCollectionPath() .
                                                                                '">By ' .
                                                                                $expertPage->getCollectionName() .
                                                                                ", " .
                                                                                $expertPage->getAttribute("title") .
                                                                                "</a></p>";
                                                                                        }
                                                                                    }
                                                                                }
                                                                                echo "</div>";
                                                                            }
                                                                            echo $thisResource["resourceContent"];
                                                                            if (
                                                        isset($thisResource["fileName"]) &&
                                                        $thisResource["fileName"] !== ""
                                                    ) {
                                                                                if ($thisResource["resourcePageType"] == 3) {
                                                                                    echo '<br><p class="btn"><a class="btn pdf" target="_blank" href="/application/files/resources/' .
                                                                $thisResource["fileName"] .
                                                                '">Open PDF</a></p>';
                                                                                } else {
                                                                                    echo '<br><p class="btn"><a target="_blank" class="btn pdf" href="/application/files/resources/assets/' .
                                                                $thisResource["fileName"] .
                                                                '">Open PDF</a></p>';
                                                                                }
                                                                            }
                                                                            if (
                                                        $thisResource["resourceType"] == 3 ||
                                                        $thisResource["resourceType"] == 9
                                                    ) {
                                                                                $parsedVideoUrl = parse_url(
                                                                                    $thisResource["resourceMediaLink"]
                                                                                );
                                                                                echo '<div class="videoholder">';
                                                                                if (
                                                            str_contains(
                                                                $parsedVideoUrl["host"],
                                                                "youtube"
                                                            ) ||
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
                                                                                        $videoId = str_replace(
                                                                                            "/",
                                                                                            "",
                                                                                            $parsedVideoUrl["path"]
                                                                                        );
                                                                                    }
                                                                                    echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' .
                                                                $videoId .
                                                                '" title="' .
                                                                $thisResource["resourceName"] .
                                                                '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                                                } elseif (
                                                            str_contains($parsedVideoUrl["host"], "vimeo")
                                                        ) {
                                                                                    $videoId = str_replace(
                                                                                        "/",
                                                                                        "",
                                                                                        $parsedVideoUrl["path"]
                                                                                    );
                                                                                    echo '<iframe src="https://player.vimeo.com/video/' .
                                                                $videoId .
                                                                '" width="640" height="367" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                                                                } else {
                                                                                    echo '<iframe src="' .
                                                                $thisResource["resourceMediaLink"] .
                                                                '" width="640" height="367" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                                                                }
                                                                                echo "</div>";
                                                                            }
                                                                            if (isset($thisResource["resourceVideoTranscript"]) && strlen($thisResource["resourceVideoTranscript"]) > 0) {
                                                                                echo '<p class="btn"><a data-fancybox="" data-src="#transcript_video" class="btn">Read transcript</a></p>';
                                                                                echo '<div class="transcript_content" id="transcript_video">';
                                                                                echo $thisResource["resourceVideoTranscript"];
                                                                                echo '</div>';
                                                                            }
                                                                            echo '<p style="text-align: center;"><a href="/insights" class="btn ghost noarrow return">Return to Insights Center</a></p>';
                                                                            echo "</div></div>";
                                                                        } ?>
                        <?php if (isset($relatedInsights) && $relatedInsights && count($relatedInsights) > 0) {
                                                                            for ($x = 1; $x <= 12; $x++) {
                                                                                if (isset($relatedInsights[$x - 1])) {
                                                                                    ${"insightId" . $x} =
                                                                $relatedInsights[$x - 1]["id"];
                                                                                } else {
                                                                                    ${"insightId" . $x} = false;
                                                                                }
                                                                            }
                                                                            $relatedInsights = BlockType::getByHandle("insight");
                                                                            $relatedInsights->controller->title =
                                                        "Related Insights";
                                                                            $relatedInsights->controller->isRelated = true;
                                                                            $relatedInsights->controller->insightId1 = $insightId1;
                                                                            $relatedInsights->controller->insightId2 = $insightId2;
                                                                            $relatedInsights->controller->insightId3 = $insightId3;
                                                                            $relatedInsights->controller->insightId4 = $insightId4;
                                                                            $relatedInsights->controller->insightId5 = $insightId5;
                                                                            $relatedInsights->controller->insightId6 = $insightId6;
                                                                            $relatedInsights->controller->insightId7 = $insightId7;
                                                                            $relatedInsights->controller->insightId8 = $insightId8;
                                                                            $relatedInsights->controller->insightId9 = $insightId9;
                                                                            $relatedInsights->controller->insightId10 = $insightId10;
                                                                            $relatedInsights->controller->insightId11 = $insightId11;
                                                                            $relatedInsights->controller->insightId12 = $insightId12;
                                                                            $relatedInsights->render("view");
                                                                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->inc("elements/footer.php"); ?>