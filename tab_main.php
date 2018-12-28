<?php

use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules\CustomFields\FieldTypes;

if (isset($request["update"])) {
    list($success, $message) = FieldTypes::updateMainTab($field_type_id, $request, $L);
}

$field_type_info = CoreFieldTypes::getFieldType($field_type_id, true);

if (isset($request["reset_field_type"])) {
	CoreFieldTypes::resetFieldTypeByIdentifier($field_type_info["field_type_identifier"]);
	$success = true;
	$message = $L["notify_field_type_reset"];
}

$field_type_groups = CoreFieldTypes::getFieldTypeGroups();
$compatible_field_sizes = explode(",", $field_type_info["compatible_field_sizes"]);

$show_reset_button = in_array($field_type_info["field_type_identifier"], array(
	"textbox", "textarea", "password", "dropdown", "multi_select_dropdown", "radio_buttons", "checkboxes",
	"date", "time", "phone", "code_markup"
));

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["show_reset_button"] = $show_reset_button;
$page_vars["page"] = $page;
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
