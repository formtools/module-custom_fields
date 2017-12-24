<?php

use FormTools\FieldTypes as CoreFieldTypes;

$success = true;
$message = "";
if (isset($request["update"])) {
    list($success, $message) = cf_update_main_tab($field_type_id, $request);
}

$field_type_info = CoreFieldTypes::getFieldType($field_type_id, true);
$field_type_groups = CoreFieldTypes::getFieldTypeGroups();
$compatible_field_sizes = explode(",", $field_type_info["compatible_field_sizes"]);

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["head_string"] = $head_string;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_groups"] = $field_type_groups;
$page_vars["compatible_field_sizes"] = $compatible_field_sizes;
$page_vars["raw_field_types"] = CoreFieldTypes::$rawFieldTypes;
$page_vars["head_js"] = <<< END
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

$module->displayPage("templates/edit.tpl", $page_vars);
