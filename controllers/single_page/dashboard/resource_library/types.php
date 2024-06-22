<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage\Dashboard\ResourceLibrary;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;
use Concrete\Package\PropertyTaxRates\EntityFactory;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Url;

defined("C5_EXECUTE") or die("Access Denied.");

class Types extends DashboardPageController
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
                $v = [$_POST["typeName"],0];
                $q = "insert into resourcelibrary_types ( typeName, typeDefault ) values (?,?)";
                $errorUrl = "/index.php/dashboard/resource_library/types/add";
            } else {
                $v = [$_POST["typeName"], $_POST["id"]];
                $q = "update resourcelibrary_types set typeName = ? where id = ?";
                $errorUrl =
          "/index.php/dashboard/resource_library/types/edit" . $_POST["id"];
            }
            if ($db->executeQuery($q, $v)) {
                $this->flash("success", t("Type successfully saved"));
                return Redirect::to("/index.php/dashboard/resource_library/types/");
            } else {
                $this->flash(
                    "flashMessage",
                    t("Your type could not be saved at this time. Please try again.")
                );
                return Redirect::to("/index.php/dashboard/resource_library/types/");
            }
        } else {
            $this->flash(
                "flashMessage",
                t("Please correct the issues below and try saving your type again.")
            );
            return Redirect::to($errorUrl);
        }
    }

    public function view(): void
    {
        $db = \Database::connection();
        $resourcesQuery = $db->fetchAll("SELECT * FROM resourcelibrary_types", []);
        $this->set("resources", $resourcesQuery);
        $this->set("pageTitle", t("Resource Types"));
        $columns[0] = [
      "columnTitle" => "Resource Type",
      "dbTitle" => "typeName",
    ];
        $columns[1] = [
      "columnTitle" => "Actions",
      "dbTitle" => "actions",
    ];
        $this->set("columns", $columns);
    }

    public function add(): void
    {
        $this->set("pageTitle", t("Add a New Resource Type"));
        $this->set("id", 0);
        $this->set("btnText", "Add Type");
        $this->set("resource", null);
        $this->set("btnClasses", ["btn-danger", "btn-primary"]);
        $this->set("readonly", null);
        $this->set("actionName", "save");
        $this->render("dashboard/resource_library/types/edit");
    }

    public function edit($id = 0)
    {
        if ($id !== 0) {
            $this->set("pageTitle", t("Edit Resource Type"));
            $this->set("id", $id);
            $this->set("readonly", null);
            $db = \Database::connection();
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_types WHERE id = ?",
                [$id]
            );
            if($resourcesQuery['typeDefault'] == 1){
                $this->flash(
                    "flashMessage",
                    t("You cannot edit a default type.")
                );
                return Redirect::to("/index.php/dashboard/resource_library/types");
            } else {
                $this->set("resource", $resourcesQuery);
                $this->set("btnClasses", ["btn-danger", "btn-primary"]);
                $this->set("btnText", "Edit Type");
                $this->set("actionName", "save");
                $this->render("dashboard/resource_library/types/edit");
            }
        } else {
            return Redirect::to("/index.php/dashboard/resource_library/types/add");
        }
    }

    public function delete($id = 0)
    {
        $db = \Database::connection();
        if ($id !== 0) {
            $this->set("pageTitle", t("Delete Resource Type"));
            $this->set("id", $id);
            $this->set("readonly", "readonly");
            $this->set(
                "message",
                "Are you sure you want to delete the following resource type?"
            );
            $resourcesQuery = $db->fetchAssoc(
                "SELECT * FROM resourcelibrary_types WHERE id = ?",
                [$id]
            );
            $this->set("btnClasses", ["btn-primary", "btn-danger"]);
            $this->set("resource", $resourcesQuery);
            $this->set("actionName", "delete");
            $this->set("btnText", "Delete Type");
            $this->render("dashboard/resource_library/types/edit");
        } else {
            if ($_POST && $_POST["id"] !== 0) {
                $v = [$_POST["id"]];
                $q = "DELETE FROM resourcelibrary_types WHERE id = ?";
                if ($db->executeQuery($q, $v)) {
                    $this->flash("flashMessage", t("Resource type deleted."));
                } else {
                    $this->flash(
                        "flashMessage",
                        t("This resource type could not be deleted. Please try again.")
                    );
                }
                return Redirect::to("/index.php/dashboard/resource_library/types/");
            } else {
                return Redirect::to("/index.php/dashboard/resource_library/types/");
            }
        }
    }

    public function validate($vals = false)
    {
        if (!$vals) {
            return false;
        } else {
            $db = \Database::connection();
            if ($vals["id"] == 0) {
                $resourcesQuery = $db->fetchAll(
                    "SELECT * FROM resourcelibrary_types WHERE typeName = ?",
                    [$vals["typeName"]]
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
