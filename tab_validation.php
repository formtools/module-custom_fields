<?php

$sortable_id = "validation_rules";
if (isset($request["delete"]))
{
  list($g_success, $g_message) = cf_delete_validation_rule($field_type_id, $request["delete"]);
}
if (isset($request["update_order"]))
{
  $request["sortable_id"] = $sortable_id;
  list($g_success, $g_message) = cf_update_validation_rule_order($request["field_type_id"], $request);
}

$field_type_info = ft_get_field_type($field_type_id, true);

$page_vars["page"]            = $page;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_id"]   = $field_type_id;
$page_vars["sortable_id"]     = $sortable_id;
$page_vars["js_messages"]     = array("phrase_please_confirm", "word_edit", "word_delete", "word_yes", "word_no");
$page_vars["module_js_messages"] = array("confirm_delete_validation_rule");
$page_vars["head_string"] =<<< END
  <script src="$g_root_url/global/scripts/sortable.js"></script>
  <script src="$g_root_url/modules/custom_fields/global/scripts/custom_fields.js"></script>
  <link type="text/css" rel="stylesheet" href="$g_root_url/modules/custom_fields/global/css/styles.css">
END;

ft_display_module_page("templates/edit.tpl", $page_vars);
