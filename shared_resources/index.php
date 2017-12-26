<?php

require_once("../../../global/library.php");

use FormTools\Core;
use FormTools\Modules;
use FormTools\Sessions;
use FormTools\Settings;
use FormTools\Modules\CustomFields\FieldTypeSettings;

$module = Modules::initModulePage("admin");
$L = $module->getLangStrings();
$root_url = Core::getRootUrl();

$sortable_id = "shared_resources_included_files";
$success = true;
$message = "";
if (isset($_POST["update"])) {
    $_POST["sortable_id"] = $sortable_id;
    list($success, $message) = FieldTypeSettings::updateSharedResources($_POST, $L);
}

$settings = Settings::get(array(
    "edit_submission_shared_resources_js",
    "edit_submission_shared_resources_css",
    "edit_submission_onload_resources"
));
$current_inner_tab = Sessions::getWithFallback("inner_tabs.shared_resources", "");

$page_vars = array(
    "g_success" => $success,
    "g_message" => $message,
    "current_inner_tab" => $current_inner_tab,
    "sortable_id" => $sortable_id,
);

// pull out the script and link tags. These are the only permitted items
preg_match_all("/\<script(.*?)?\>(.|\\n)*?\<\/script\>/i", $settings["edit_submission_onload_resources"], $script_tag_matches);
$script_tags = $script_tag_matches[0];
preg_match_all("/\<link[^\>]*\/?>/i", $settings["edit_submission_onload_resources"], $link_tag_matches);
$link_tags = $link_tag_matches[0];
$resources = array_merge($script_tags, $link_tags);

$page_vars["edit_submission_shared_resources_js"] = $settings["edit_submission_shared_resources_js"];
$page_vars["edit_submission_shared_resources_css"] = $settings["edit_submission_shared_resources_css"];
$page_vars["edit_submission_onload_resources"] = $resources;

$page_vars["head_js"] = <<< END
$(function() {
    var onChangeTab = function (tab) {
        if (tab === 1) {
            edit_submission_shared_resources_css_field.refresh();
        } else if (tab === 2) {
            edit_submission_shared_resources_js_field.refresh();
        }
    };
    ft.init_inner_tabs(onChangeTab);
});
END;

$module->displayPage("templates/shared_resources/index.tpl", $page_vars);
