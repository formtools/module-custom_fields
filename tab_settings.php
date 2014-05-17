<?php

$sortable_id = "list_settings";

if (isset($request["update"]))
{
  // bit kludgy. This function deletes the appropriate rows, but the following function handles the message
  if (isset($request["{$sortable_id}_sortable__deleted_rows"]))
  {
    cf_delete_field_type_settings($request["field_type_id"], $request["{$sortable_id}_sortable__deleted_rows"]);
  }

  $request["sortable_id"] = $sortable_id;
  list($g_success, $g_message) = cf_update_field_type_setting_order($request["field_type_id"], $request);
}

$field_type_info     = ft_get_field_type($field_type_id);
$field_type_settings = ft_get_field_type_settings($field_type_id);

$page_vars["page"]            = $page;
$page_vars["sortable_id"]     = $sortable_id;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_settings"] = $field_type_settings;
$page_vars["head_string"] =<<< END
  <script type="text/javascript" src="$g_root_url/global/scripts/sortable.js"></script>
  <link type="text/css" rel="stylesheet" href="$g_root_url/modules/custom_fields/global/css/styles.css">
END;

ft_display_module_page("templates/edit.tpl", $page_vars);
