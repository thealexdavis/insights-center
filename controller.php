<?php
/**
 * Package Controller File
 *
 *
 *
 * @package  Resource Library
 * @author   Alex Davis
 * @link     http://www.github.com/thealexdavis
 */

namespace Concrete\Package\ResourceLibrary;

use Package;
use BlockType;
use Asset;
use AssetList;
use Loader;
use Concrete\Package\ResourceLibrary\Controller\SinglePage\Dashboard\ResourceLibrary\Edit;
use SinglePage;
use View;
use Page;
use Events;
use Response;
use UserInfo;
use Core;
use User;
use Route;
use DateTime;
use CollectionAttributeKey;
use Concrete\Attribute\Select\Controller as SelectAttributeTypeController;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Attribute\Select\Option as SelectAttributeTypeOption;
use Aembler\Resources\RouteList;

defined("C5_EXECUTE") or die("Access Denied.");

class Controller extends Package
{
    /**
     * Package Handle
     *
     * @var string
     */
    protected $pkgHandle = "resource_library";

    /**
     * Application Version Required
     *
     * @var string
     */
    protected $appVersionRequired = "5.7.1";

    /**
     * Package Version
     *
     * @var string
     */
    protected $pkgVersion = "0.6";

    /**
     * Package Name
     *
     * @return string
     */

    protected static $blockTypes = [[]];

    protected $pkgAutoloaderRegistries = [
    "src/" => "Aembler\\Resources",
  ];

    public function getPackageName()
    {
        return t("Resource Library");
    }

    /**
     * Package Description
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t("Create and edit content within a resource library");
    }

    /**
     * Install Hook
     *
     * @return void
     */
    public function install()
    {
        $pkg = parent::install(); //this will automatically install our package-level db.xml schema for us (among other things)

        /*
                                foreach (self::$blockTypes as $blockType) {
                                    $existingBlockType = BlockType::getByHandle($blockType['handle']);
                                    if (!$existingBlockType) {
                                        BlockType::installBlockTypeFromPackage($blockType['handle'], $pkg);
                                    }
                                    if (isset($blockType['set']) && $blockType['set']) {
                                        $navigationBlockTypeSet = BlockTypeSet::getByHandle($blockType['set']);
                                        if ($navigationBlockTypeSet) {
                                            $navigationBlockTypeSet->addBlockType(BlockType::getByHandle($blockType['handle']));
                                        }
                                    }
                                }
*/

        $this->installOrUpgrade($pkg);
    }

    private function installOrUpgrade($pkg)
    {
        $this->getOrAddSinglePage(
            $pkg,
            "/dashboard/resource_library",
            "Resource Library"
        );
        $this->getOrAddSinglePage(
            $pkg,
            "/dashboard/resource_library/resources",
            "Manage Resources"
        );
        $this->getOrAddSinglePage(
            $pkg,
            "/dashboard/resource_library/types",
            "Manage Types"
        );
        $this->getOrAddSinglePage(
            $pkg,
            "/dashboard/resource_library/topics",
            "Manage Topics and Subtopics"
        );
        $this->getOrAddSinglePage(
            $pkg,
            "/dashboard/resource_library/assets",
            "Manage Assets"
        );
        $this->getOrAddSinglePage($pkg, "/insights", "Insights");
    }

    private function getOrAddSinglePage(
        $pkg,
        $cPath,
        $cName = "",
        $cDescription = ""
    ) {
        Loader::model("single_page");

        $sp = SinglePage::add($cPath, $pkg);

        if (is_null($sp)) {
            //SinglePage::add() returns null if page already exists
            $sp = Page::getByPath($cPath);
        } else {
            //Set page title and/or description...
            $data = [];
            if (!empty($cName)) {
                $data["cName"] = $cName;
            }
            if (!empty($cDescription)) {
                $data["cDescription"] = $cDescription;
            }

            if (!empty($data)) {
                $sp->update($data);
            }
        }
        return $sp;
    }

    public function uninstall()
    {
        parent::uninstall();

        //Manually remove database tables (C5 doesn't do this automatically)
    $table_prefix = "resourcelibrary_"; //<--make sure this is unique enough to not accidentally drop other tables!
    $db = Loader::db();
        $tables = $db->GetCol("SHOW TABLES LIKE '{$table_prefix}%'");
        $sql = "DROP TABLE " . implode(",", $tables);
        $db->Execute($sql);
    }

    public function on_start()
    {
        $router = $this->app->make("router");
        $list = new RouteList();
        $list->loadRoutes($router);
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();
        $this->installOrUpgrade($pkg);
    }
}
