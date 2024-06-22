/******/ (() => {
  // webpackBootstrap
  var __webpack_exports__ = {};
  // This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
  (() => {
    /*!****************************************!*\
  !*** ./resources/insights/featured.js ***!
  \****************************************/
  })();

  // This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
  (() => {
    /*!*****************************************!*\
  !*** ./resources/insights/resources.js ***!
  \*****************************************/
    offsetValue = 0;
    var insightQuery = document.querySelector("#insight_filter_query");
    var filterType = document.querySelector("select#resourceFilterType");
    var filterTopic = document.querySelector("select#resourceFilterTopic");
    var filterAreas = document.querySelector("select#resourceTherapeuticAreas");
    var loadMoreHolder = document.querySelector(".load_more_holder");
    var loadMoreBtn = document.querySelector(".load_more_holder p.loadmore");
    var resourcesList = document.getElementById("resources_list");
    var loadBtn = document.getElementById("load_more_resources_btn");
    var filtersForm = document.getElementById("insightfilter");
    var resourcesAddList = document.getElementById("insert_resources_row");
    if (resourcesList) {
      resourcesCount = parseInt(resourcesList.dataset.count);
    } else {
      resourcesCount = 0;
    }
    setTimeout(function () {
      var gdprCountries = [
        "Austria",
        "Belgium",
        "Bulgaria",
        "Croatia",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Estonia",
        "Finland",
        "France",
        "Germany",
        "Greece",
        "Hungary",
        "Iceland",
        "Ireland",
        "Italy",
        "Latvia",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Malta",
        "Netherlands",
        "Norway",
        "Poland",
        "Portugal",
        "Romania",
        "Slovakia",
        "Slovenia",
        "Spain",
        "Sweden",
        "Switzerland",
        "United Kingdom",
        "Australia",
        "New Zealand",
        "South Korea",
        "Thailand",
        "Greater China Region",
        "Hong Kong",
        "Taiwan",
        "Japan",
        "Korea",
        "China"
      ];
      var countriesDropdown = document.querySelector(
        "#gated_form_obj .form-group select[name='country']"
      );
      var gdprObjects = document.querySelector(".gdpr-box");
      if (countriesDropdown) {
        countriesDropdown.addEventListener("change", function () {
          var thisCountryChosen =
            countriesDropdown.options[countriesDropdown.selectedIndex].text;
          if (gdprCountries.includes(thisCountryChosen)) {
            gdprObjects.style.display = "block";
            document.querySelector("#gdpr_accept").required = true;
          } else {
            gdprObjects.style.display = "none";
            document.querySelector("#gdpr_accept").required = false;
          }
        });
        document
          .querySelector("#gdpr_accept")
          .addEventListener("change", function () {
            document.getElementById("GDPR_Consent_Verbiage").value =
              "I would like to receive marketing communications from [COMPANY NAME] and consent to the processing of the personal data that I provide in accordance with and as described in the Privacy Policy.";
            document.getElementById("GDPR_Consent_Status").value = "";
          });
        document
          .querySelector("#gdpr_decline")
          .addEventListener("change", function () {
            document.getElementById("GDPR_Consent_Verbiage").value = "";
            document.getElementById("GDPR_Consent_Status").value =
              "If accept is true, pass the value Opted In. If decline is true, pass the value Opted Out";
          });
      }
    }, 1000);
    function performFilter() {
      contentTypeSearch = filterType.value;
      topicSearch = filterTopic.value;
      areaSearch = filterAreas.value;
      queryValue = insightQuery.value;
      searchObj = {
        query: queryValue,
        type: contentTypeSearch,
        topic: topicSearch,
        area: areaSearch,
        offset: offsetValue
      };
      fetch("/resources/find", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json"
        },
        body: JSON.stringify(searchObj)
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (response) {
          return addNewResources(response);
        });
    }
    function addNewResources(resources) {
      var startCount = 1;
      var resourceHtmlString = "";
      if (resources == null) {
        // removeLoadMsg();
        loadMoreBtn.style.display = "none";
        addMsg(
          "No results were found. Please search again or choose different filters."
        );
      } else {
        resources.forEach(function (data) {
          if (!data.counter) {
            loadMoreBtn.style.display = "none";
          } else {
            loadMoreBtn.style.display = "block";
          }
          typeName = data.typeName;
          typeSlug = typeName.replace(" ", "-").toLowerCase();
          svgSlug = typeSlug.replace("-", "").toLowerCase();
          if (startCount == 1) {
            resourceHtmlString += '<div class="row" id="row_added">';
          } else {
            resourceHtmlString += "";
          }
          if (startCount !== 3) {
            var offsetNum = "col-offset-2";
          } else {
            var offsetNum = "";
          }
          resourceHtmlString +=
            '<div class="col col-sm-50 col-md-15 ' +
            offsetNum +
            '"><a href="' +
            data.url +
            '" class="insight_block added_insight height-2 white none">';
          if (data.isGated && data.isGated !== 0 && data.isGated !== "0") {
            resourceHtmlString +=
              '<p class="exclusive"><span class="burst"><span>&check;</span></span><span class="word">EXCLUSIVE</span></p>';
          }
          resourceHtmlString +=
            '<div class="container"><p class="type"><img src="/packages/resource_library/img/icons/icon-' +
            svgSlug +
            '.svg">  ' +
            data.typeName +
            '</p><div class="img" style="background-image: url(' +
            data.thumbnail +
            ');"></div><p class="title">' +
            data.name +
            "</p></div></a></div>";
          if (startCount == 3) {
            resourceHtmlString += "</div>";
            startCount = 0;
          }
          startCount++;
        });
        resourcesAddList.insertAdjacentHTML("beforeend", resourceHtmlString);
        offsetValue = offsetValue + 9;
        removeLoadMsg();
      }
    }
    if (filterType) {
      filterType.onchange = function () {
        filtersSubmitResources();
      };
    }
    if (filterTopic) {
      filterTopic.onchange = function () {
        filtersSubmitResources();
      };
    }
    if (filterAreas) {
      filterAreas.onchange = function () {
        filtersSubmitResources();
      };
    }
    if (filtersForm) {
      filtersForm.addEventListener("submit", function (e) {
        e.preventDefault();
        filtersSubmitResources();
      });
    }
    function filtersSubmitResources() {
      hideTopResources();
      removeAllResources();
      offsetValue = 0;
      performFilter();
      removeLoadMsg();
      addMsg("Loading results...");
    }
    function removeLoadMsg() {
      if (document.getElementById("loadingtextmsg")) {
        document.getElementById("loadingtextmsg").remove();
      }
    }
    function hideTopResources() {
      var get = document.querySelectorAll(".initial_insight");
      get.forEach(function (element) {
        element.style.display = "none";
      });
    }
    function removeAllResources() {
      var get = document.querySelectorAll("#row_added");
      get.forEach(function (element) {
        element.remove();
      });
    }
    if (loadBtn) {
      loadBtn.addEventListener("click", function (e) {
        e.preventDefault();
        performFilter();
        removeLoadMsg();
        addMsg("Loading results...");
      });
    }
    function addMsg(msg) {
      msgString =
        '<h3 class="loadingtext" id="loadingtextmsg" style="text-align: center;">' +
        msg +
        "</h3>";
      loadMoreHolder.insertAdjacentHTML("beforeBegin", msgString);
    }
    var urlParams = new URLSearchParams(window.location.search);
    var queryParameter = urlParams.get("query");
    var areaParameter = urlParams.get("area");
    var topicParameter = urlParams.get("topic");
    var typeParameter = urlParams.get("type");
    if (
      queryParameter !== null ||
      areaParameter !== null ||
      topicParameter !== null ||
      typeParameter !== null
    ) {
      setTimeout(function () {
        filtersSubmitResources();
      }, 5);
    }
  })();

  /******/
})();
//# sourceMappingURL=main.js.map
