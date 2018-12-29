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
	protected $version = "2.1.0";
	protected $date = "2018-12-28";
	protected $originLanguage = "en_us";
	protected $cssFiles = array(
		"{MODULEROOT}/css/styles210.css",
		"{FTROOT}/global/codemirror/lib/codemirror.css"
	);
	protected $jsFiles = array(
		"{FTROOT}/global/codemirror/lib/codemirror.js",
		"{FTROOT}/global/scripts/sortable.js",
		"{FTROOT}/global/codemirror/mode/xml/xml.js",
		"{FTROOT}/global/codemirror/mode/smarty/smarty.js",
		"{FTROOT}/global/codemirror/mode/htmlmixed/htmlmixed.js",
		"{FTROOT}/global/codemirror/mode/css/css.js",
		"{FTROOT}/global/codemirror/mode/javascript/javascript.js",
		"{FTROOT}/global/codemirror/mode/clike/clike.js",
		"{MODULEROOT}/scripts/custom_fields.js"
	);

	protected $nav = array(
		"module_name" => array("index.php", false),
		"phrase_shared_resources" => array("shared_resources/", false),
		"phrase_shared_characteristics" => array("shared_characteristics.php", false)
	);

	public function install($module_id)
	{
		return array(true, "");
	}
}
