<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage\Dashboard\ResourceLibrary;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;
use Concrete\Package\PropertyTaxRates\EntityFactory;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Url;

defined("C5_EXECUTE") or die("Access Denied.");

class Topics extends DashboardPageController
{
    /**
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function save(): RedirectResponse
    {
        if ($this->validate($_POST)) {
            $db = \Database::connection();
            if ($_POST["id"] == 0) {
                $v = [$_POST["topicName"]];
                $q = "insert into resourcelibrary_topics ( topicName ) values (?)";
                $errorUrl = "/index.php/dashboard/resource_library/topics/add";
            } else {
                $v = [$_POST["topicName"], $_POST["id"]];
                $q = "update resourcelibrary_topics set topicName = ? where id = ?";
                $errorUrl =
          "/index.php/dashboard/resource_library/topics/edit" . $_POST["id"];
            }
            $thisTopic = $db->executeQuery($q, $v);
            $newId = $_POST["id"] == 0 ? $db->Insert_ID() : $_POST["id"];
            $this->saveSubtopics($_POST["topicSubtopic"], $newId, $_POST["id"]);
            if ($thisTopic) {
                $this->flash("success", t("Topic successfully saved"));
                return Redirect::to("/index.php/dashboard/resource_library/topics/");
            } else {
                $this->flash(
                    "flashMessage",
                    t("Your topic could not be saved at this time. Please try again.")
                );
                return Redirect::to("/index.php/dashboard/resource_library/topics/");
            }
        } else {
            $this->flash(
                "flashMessage",
                t("Please correct the issues below and try saving your topic again.")
            );
            $errorUrl =
        "/index.php/dashboard/resource_library/topics/edit" . $_POST["id"];
            return Redirect::to($errorUrl);
        }
    }

    public function view(): void
    {
        $db = \Database::connection();
        $resourcesQuery = $db->fetchAll("SELECT * FROM resourcelibrary_topics", []);
        $this->set("resources", $resourcesQuery);
        $this->set("pageTitle", t("Resource Topics"));
        $columns[0] = [
      "columnTitle" => "Resource Topics",
      "dbTitle" => "topicName",
    ];
        $columns[1] = [
      "columnTitle" => "Number of Subtopics",
      "dbTitle" => "subtopics",
    ];
        $columns[2] = [
      "columnTitle" => "Actions",
      "dbTitle" => "actions",
    ];
        $this->set("columns", $columns);
    }

    public function add(): void
    {
        $this->set("pageTitle", t("Add a New Resource Topic"));
        $this->set("id", 0);
        $this->set("btnText", "Add Topic");
        $this->set("resource", null);
        $this->set("btnClasses", ["btn-danger", "btn-primary"]);
        $this->set("readonly", null);
        $this->set("subtopic", null);
        $this->set("actionName", "save");
        $this->render("dashboard/resource_library/topics/edit");
    }

    public function edit($id = 0)
    {
        if ($id !== 0) {
            $this->set("pageTitle", t("Edit Resource Topic"));
            $this->set("id", $id);
            $this->set("readonly", null);
            $db = \Database::connection();
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_topics WHERE id = ?",
                [$id]
            );
            $subtopicsQuery = $db->fetchAll(
                "SELECT * FROM resourcelibrary_subtopics WHERE topicId = ?",
                [$id]
            );
            $this->set("resource", $resourcesQuery);
            $this->set("subtopics", $subtopicsQuery);
            $this->set("btnClasses", ["btn-danger", "btn-primary"]);
            $this->set("btnText", "Edit Topic");
            $this->set("actionName", "save");
            $this->render("dashboard/resource_library/topics/edit");
        } else {
            return Redirect::to("/index.php/dashboard/resource_library/topics/add");
        }
    }

    public function delete($id = 0)
    {
        $db = \Database::connection();
        if ($id !== 0) {
            $this->set("pageTitle", t("Delete Resource Topic"));
            $this->set("id", $id);
            $this->set("readonly", "readonly");
            $this->set(
                "message",
                "Are you sure you want to delete the following resource topic?"
            );
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_topics WHERE id = ?",
                [$id]
            );
            $this->set("btnClasses", ["btn-primary", "btn-danger"]);
            $this->set("resource", $resourcesQuery);
            $this->set(
                "subtopic",
                str_replace(",", "\n", $resourcesQuery["subtopics"])
            );
            $this->set("actionName", "delete");
            $this->set("btnText", "Delete Topic");
            $this->render("dashboard/resource_library/topics/edit");
        } else {
            if ($_POST && $_POST["id"] !== 0) {
                $v = [$_POST["id"]];
                $q = "DELETE FROM resourcelibrary_topics WHERE id = ?";
                if ($db->executeQuery($q, $v)) {
                    $this->flash("flashMessage", t("Resource Topic deleted."));
                } else {
                    $this->flash(
                        "flashMessage",
                        t("This resource topic could not be deleted. Please try again.")
                    );
                }
                return Redirect::to("/index.php/dashboard/resource_library/topics/");
            } else {
                return Redirect::to("/index.php/dashboard/resource_library/topics/");
            }
        }
    }

    public function saveSubtopics($subtopics, $topicId, $originalId)
    {
        $db = \Database::connection();
        if ($originalId == 0) {
            foreach ($subtopics as $subtopic) {
                $v = [$subtopic["name"], $topicId];
                $q =
          "insert into resourcelibrary_subtopics ( subTopicName, topicId ) values (?, ?)";
                $db->executeQuery($q, $v);
            }
        } else {
            foreach ($subtopics as $subtopic) {
                if ($subtopic["id"] == 0) {
                    $v = [$subtopic["name"], $originalId];
                    $q =
            "insert into resourcelibrary_subtopics ( subTopicName, topicId ) values (?, ?)";
                } else {
                    $v = [$subtopic["name"], $subtopic["id"]];
                    $q =
            "update resourcelibrary_subtopics set subTopicName = ? where id = ?";
                }
                $db->executeQuery($q, $v);
            }
        }
    }

    public function returnSubTopics($id)
    {
        $db = \Database::connection();
        $subtopicsQuery = $db->fetchAll(
            "SELECT * FROM resourcelibrary_subtopics WHERE topicId = ?",
            [$id]
        );
        return count($subtopicsQuery);
    }

    public function validate($vals = false)
    {
        if (!$vals) {
            return false;
        } else {
            $db = \Database::connection();
            if ($vals["id"] == 0) {
                $resourcesQuery = $db->fetchAll(
                    "SELECT * FROM resourcelibrary_topics WHERE topicName = ?",
                    [$vals["topicName"]]
                );

                if (empty($resourcesQuery)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }
}
