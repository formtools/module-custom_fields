<?php

if (isset($request["update"]))
{
	list($g_success, $g_message) = cf_update_main_tab($field_type_id, $request);
}

$field_type_info   = ft_get_field_type($field_type_id, true);
$field_type_groups = ft_get_field_type_groups(false);
$compatible_field_sizes = explode(",", $field_type_info["compatible_field_sizes"]);

$page_vars["page"]            = $page;
$page_vars["head_string"]     = $head_string;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_groups"] = $field_type_groups;
$page_vars["compatible_field_sizes"] = $compatible_field_sizes;
$page_vars["raw_field_types"] = $g_raw_field_types;
$page_vars["head_js"] =<<< END
$(function() {
  $("#raw_field_type_map").bind("change keyup", function() {
    var selected = $(this).val();
    if ($.inArray(selected, ["radio-buttons", "checkboxes", "select", "multi-select"]) != -1) {
      $("#raw_field_type_map_multi_select_id").removeClass("hidden");
    } else {
      $("#raw_field_type_map_multi_select_id").addClass("hidden");
    }
  });
});
END;

ft_display_module_page("templates/edit.tpl", $page_vars);
