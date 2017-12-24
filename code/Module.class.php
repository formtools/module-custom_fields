<?php


namespace FormTools\Modules\CustomFields;

use FormTools\Module as FormToolsModule;


class Module extends FormToolsModule
{
    protected $moduleName = "Custom Fields";
    protected $moduleDesc = "";
    protected $author = "Ben Keen";
    protected $authorEmail = "ben.keen@gmail.com";
    protected $authorLink = "https://formtools.org";
    protected $version = "2.0.0";
    protected $date = "2017-12-23";
    protected $originLanguage = "en_us";

    protected $nav = array(
        "module_name"                   => array("index.php", false),
        "phrase_shared_resources"       => array("shared_resources/", false),
        "phrase_shared_characteristics" => array("shared_characteristics.php", false)
    );

    public function install($module_id)
    {
        return array(true, "");
    }
}
