<?php

use FormTools\FieldTypes;
use FormTools\Modules;
use FormTools\Modules\CustomFields\Validation;

$rule_id = Modules::loadModuleField("custom_fields", "rule_id", "rule_id");

if (isset($_GET["new"])) {
    $success = true;
    $message = $L["notify_validation_rule_added"];
}

if (isset($request["update"])) {
    list($success, $message) = Validation::updateValidationRule($request["rule_id"], $request, $L);
}

$validation_rule = Validation::getValidationRule($rule_id);
$field_type_info = FieldTypes::getFieldType($field_type_id, true);

// to prevent the user defining multiple rules for the same RSV validation rule (which wouldn't make sense), figure out
// what's already been created and pass them to the RSV dropdown, so they can't be selected
$existing_validation_rules = array();
foreach ($field_type_info["validation"] as $rules) {
    if ($validation_rule["rsv_rule"] != $rules["rsv_rule"]) {
        $existing_validation_rules[] = $rules["rsv_rule"];
    }
}

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["rule"] = $validation_rule;
$page_vars["existing_validation_rules"] = $existing_validation_rules;
$page_vars["js_messages"] = array("phrase_connect_rows", "phrase_disconnect_rows");
$page_vars["head_js"] = <<< END
var rules = [];
rules.push("required,rsv_rule,{$L["validation_no_validation_type"]}");
rules.push("required,rule_label,{$L["validation_no_rule_label"]}");
rules.push("required,default_error_message,{$L["validation_no_default_error_message"]}");
rules.push("if:rsv_rule=function,required,custom_function,{$L["validation_no_custom_function"]}");

$(function() {
  $("#rsv_rule").bind("change keyup", cf_ns.select_validation_rule);
});
END;

$module->displayPage("templates/edit.tpl", $page_vars);
