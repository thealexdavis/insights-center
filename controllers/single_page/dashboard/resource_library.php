<?php

namespace Concrete\Package\ResourceLibrary\Controller\SinglePage\Dashboard;

defined("C5_EXECUTE") or die(_("Access Denied."));

use Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use BlockType;
use Environment;
use View;
use Controller;
use User;
use DateTime;
use Page;
use CollectionAttributeKey;
use Concrete\Attribute\Select\Controller as SelectAttributeTypeController;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Attribute\Select\Option as SelectAttributeTypeOption;

class ResourceLibrary extends DashboardPageController
{
    public $helpers = ["form"];

    public function view()
    {
        $this->redirect("/dashboard/resource_library/resources/");
    }
}
