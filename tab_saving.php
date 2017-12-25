<?php

use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules\CustomFields\FieldTypes;

if (isset($request["update"])) {
    list($success, $message) = FieldTypes::updateServerTab($field_type_id, $request, $L);
}
$field_type_info = CoreFieldTypes::getFieldType($field_type_id);

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["field_type_info"] = $field_type_info;

$module->displayPage("templates/edit.tpl", $page_vars);
