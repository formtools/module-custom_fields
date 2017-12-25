<?php

require_once("../../../global/library.php");

use FormTools\Core;
use FormTools\Modules;
use FormTools\Sessions;
use FormTools\Settings;

$module = Modules::initModulePage("admin");
$root_url = Core::getRootUrl();

$sortable_id = "shared_resources_included_files";

if (isset($_POST["update"])) {
    $_POST["sortable_id"] = $sortable_id;
    list($g_success, $g_message) = cf_update_shared_resources($_POST);
}

$settings = Settings::get(array(
    "edit_submission_shared_resources_js",
    "edit_submission_shared_resources_css",
    "edit_submission_onload_resources"
));
$current_inner_tab = Sessions::getWithFallback("inner_tabs.shared_resources", "");

$page_vars = array(
    "current_inner_tab" => $current_inner_tab,
    "sortable_id" => $sortable_id,
);

$page_vars["edit_submission_shared_resources_js"] = $settings["edit_submission_shared_resources_js"];
$page_vars["edit_submission_shared_resources_css"] = $settings["edit_submission_shared_resources_css"];
$page_vars["edit_submission_onload_resources"] = explode("|", $settings["edit_submission_onload_resources"]);

$page_vars["head_js"] = <<< END
$(function() {
    ft.init_inner_tabs();
});
END;

$module->displayPage("templates/shared_resources/index.tpl", $page_vars);
