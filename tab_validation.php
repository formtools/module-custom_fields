<?php

use FormTools\FieldTypes;
use FormTools\Modules\CustomFields\Validation;

$sortable_id = "validation_rules";
if (isset($request["delete"])) {
    list($success, $message) = Validation::deleteValidationRule($request["delete"], $L);
}
if (isset($request["update_order"])) {
    $request["sortable_id"] = $sortable_id;
    list($success, $message) = Validation::updateValidationRuleOrder($request, $L);
}

$field_type_info = FieldTypes::getFieldType($field_type_id, true);

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_id"] = $field_type_id;
$page_vars["sortable_id"] = $sortable_id;
$page_vars["js_messages"] = array(
    "phrase_please_confirm", "word_edit", "word_delete", "word_yes", "word_no"
);
$page_vars["module_js_messages"] = array("confirm_delete_validation_rule");

$module->displayPage("templates/edit.tpl", $page_vars);
