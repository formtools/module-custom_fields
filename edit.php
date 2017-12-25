<?php

require_once("../../global/library.php");

use FormTools\Core;
use FormTools\Modules\CustomFields\FieldTypes;
use FormTools\General as CoreGeneral;
use FormTools\Modules;

$module = Modules::initModulePage("admin");
$LANG = Core::$L;
$L = $module->getLangStrings();
$root_url = Core::getRootUrl();

$field_type_id = Modules::loadModuleField("custom_fields", "field_type_id", "field_type_id");

// ------------------------------------------------------------------------------------------------
// Super-duper top secret setting. If this is enabled, it lets the administrator manually edit
// fields marked as "non-editable" via the Custom Fields interface. DON'T EVER use this! It was
// added during development of Form Tools 2.1, to allow quick and easy configuration and testing of
// the various field types through the the interface. Unless you know what you're doing, you can mess
// up the fields types needed by the Core script. This would be bad.
$g_cf_allow_editing_of_non_editable_fields = false;
// ------------------------------------------------------------------------------------------------

// store the current selected tab in memory - except for pages which require additional
// query string info. For those, use the parent page
if (isset($request["page"]) && !empty($request["page"])) {
    $remember_page = $request["page"];
    switch ($remember_page) {
        case "add_setting":
        case "edit_setting":
            $remember_page = "settings";
            break;
    }

    $_SESSION["ft"]["custom_fields"]["page"] = $remember_page;
    $page = $request["page"];
} else {
    $page = Modules::loadModuleField("custom_fields", "page", "page", "main");
}

$same_page = CoreGeneral::getCleanPhpSelf();
$tabs = array(
    "main" => array(
        "tab_label" => $LANG["word_main"],
        "tab_link" => "{$same_page}?page=main",
        "pages" => array("main")
    ),
    "displaying" => array(
        "tab_label" => $L["word_displaying"],
        "tab_link" => "{$same_page}?page=displaying",
        "pages" => array("displaying")
    ),
    "server" => array(
        "tab_label" => $L["word_saving"],
        "tab_link" => "{$same_page}?page=saving",
        "pages" => array("saving")
    ),
    "validation" => array(
        "tab_label" => $LANG["word_validation"],
        "tab_link" => "{$same_page}?page=validation",
        "pages" => array("validation", "add_validation_rule", "edit_validation_rule")
    ),
    "settings" => array(
        "tab_label" => $LANG["word_settings"],
        "tab_link" => "{$same_page}?page=settings",
        "pages" => array("settings", "add_setting", "edit_setting")
    )
);

$links = FieldTypes::getFieldTypePrevNextLinks($field_type_id);
$prev_tabset_link = (!empty($links["prev_field_type_id"])) ? "edit.php?page=$page&field_type_id={$links["prev_field_type_id"]}" : "";
$next_tabset_link = (!empty($links["next_field_type_id"])) ? "edit.php?page=$page&field_type_id={$links["next_field_type_id"]}" : "";

// start compiling the common page vars here
$page_vars = array();
$page_vars["tabs"] = $tabs;
$page_vars["show_tabset_nav_links"] = true;
$page_vars["prev_tabset_link"] = $prev_tabset_link;
$page_vars["next_tabset_link"] = $next_tabset_link;
$page_vars["g_cf_allow_editing_of_non_editable_fields"] = $g_cf_allow_editing_of_non_editable_fields;

// can be overridden in the individual tab pages
$success = true;
$message = "";

// move this to separate include
switch ($page) {
    case "main":
        require_once("tab_main.php");
        break;
    case "displaying":
        require_once("tab_displaying.php");
        break;
    case "saving":
        require_once("tab_saving.php");
        break;
    case "settings":
        require_once("tab_settings.php");
        break;
    case "validation":
        require_once("tab_validation.php");
        break;
    case "add_validation_rule":
        require_once("tab_add_validation_rule.php");
        break;
    case "edit_validation_rule":
        require_once("tab_edit_validation_rule.php");
        break;
    case "add_setting":
        require_once("tab_add_setting.php");
        break;
    case "edit_setting":
        require_once("tab_edit_setting.php");
        break;
    default:
        require_once("tab_main.php");
        break;
}
