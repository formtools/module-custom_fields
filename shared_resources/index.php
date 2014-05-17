<?php

require_once("../../../global/library.php");
ft_init_module_page();
$sortable_id = "shared_resources_included_files";

if (isset($_POST["update"]))
{
  $_POST["sortable_id"] = $sortable_id;
  list($g_success, $g_message) = cf_update_shared_resources($_POST);
}

$settings = ft_get_settings(array("edit_submission_shared_resources_js", "edit_submission_shared_resources_css", "edit_submission_onload_resources"));
$current_inner_tab = isset($_SESSION["ft"]["inner_tabs"]["shared_resources"]) ? $_SESSION["ft"]["inner_tabs"]["shared_resources"] : "";

$page_vars = array();
$page_vars["current_inner_tab"] = $current_inner_tab;
$page_vars["sortable_id"] = $sortable_id;
$page_vars["head_string"] =<<< EOF
<script src="$g_root_url/global/codemirror/js/codemirror.js"></script>
<script src="$g_root_url/global/scripts/sortable.js"></script>
<script src="$g_root_url/modules/custom_fields/global/scripts/custom_fields.js"></script>
<link type="text/css" rel="stylesheet" href="$g_root_url/modules/custom_fields/global/css/styles.css">
EOF;

$page_vars["edit_submission_shared_resources_js"]  = $settings["edit_submission_shared_resources_js"];
$page_vars["edit_submission_shared_resources_css"] = $settings["edit_submission_shared_resources_css"];
$page_vars["edit_submission_onload_resources"]     = explode("|", $settings["edit_submission_onload_resources"]);

$page_vars["head_js"] =<<< EOF
$(function() {
  ft.init_inner_tabs();
});
EOF;

ft_display_module_page("templates/shared_resources/index.tpl", $page_vars);
