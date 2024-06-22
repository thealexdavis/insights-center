<?php

namespace Concrete\Package\ResourceLibrary\Attribute;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Entity\Attribute\Value\Value\Value;
use Concrete\Core\Attribute\Controller as AttributeController;
use Concrete\Core\Attribute\DefaultController;
use Concrete\Core\Entity\Attribute\Key\Settings\TextSettings;
use Concrete\Attribute\Address\Controller as Address;
use Core;
use Loader;
use Page;
use Database;
use Concrete\Core\Attribute\Context\BasicFormContext;
use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Concrete\Core\Attribute\Form\Control\View\GroupedView;
use Concrete\Core\Attribute\MulticolumnTextExportableAttributeInterface;
use Concrete\Core\Entity\Attribute\Key\Settings\AddressSettings;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Form\Context\ContextInterface;
use Concrete\Core\Geolocator\GeolocationResult;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Localization\Service\CountryList;
use Concrete\Core\Localization\Service\StatesProvincesList;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Attribute\Key\Key;
use Concrete\Core\Attribute\AttributeInterface;
use Concrete\Core\Entity\Attribute\Value\Value\TextValue;
use Doctrine\ORM\Mapping as ORM;

class Controller extends AttributeController
{
  public function getIconFormatter()
  {
    return new FontAwesomeIconFormatter("tag");
  }

  public function form()
  {
    $finalVal = [];
    if (is_object($this->attributeValue)) {
      $value = Core::make("helper/text")->entities(
        $this->getAttributeValue()->getValue()
      );
      $cleanVal = preg_replace("/[^0-9,.a-zA-Z_]+/", "", $value);
      $cleanVal = str_replace("quot", "", $cleanVal);
      $finalVal = explode(",", $cleanVal);
    }
    $this->set("resourceTopics", $this->getTopics());
    $this->set("resourceSubTopics", $this->getSubTopics());
    $this->set("resourceTopicsChosen", null);
    $this->set("resourceSubTopicsChosen", null);
    $this->set("fieldPostName", $this->field("value"));
    $this->set("finalVal", $finalVal);
  }

  public function getDisplayValue()
  {
    return Core::make("helper/text")->entities(
      $this->attributeValue->getValue()
    );
  }

  public function getAttributeValueClass()
  {
    return TextValue::class;
  }

  public function getTopics()
  {
    $db = \Database::connection();
    $topicsQuery = $db->fetchAll("SELECT * FROM resourcelibrary_topics", []);
    return $topicsQuery;
  }

  public function getSubTopics()
  {
    $db = \Database::connection();
    $topicsQuery = $db->fetchAll("SELECT * FROM resourcelibrary_subtopics", []);
    return $topicsQuery;
  }

  // run when we call setAttribute(), instead of saving through the UI
  public function createAttributeValue($value)
  {
    $av = new TextValue();
    $av->setValue($value);

    return $av;
  }

  public function createAttributeValueFromRequest()
  {
    $data = $this->post();

    return $this->createAttributeValue(
      isset($data["value"]) ? json_encode($data["value"]) : null
    );
  }
}
