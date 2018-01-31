<?php

use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules\CustomFields\FieldTypeSettings;

$sortable_id = "list_settings";

if (isset($request["update"])) {
    // bit kludgy. This function deletes the appropriate rows, but the following function handles the message
    if (isset($request["{$sortable_id}_sortable__deleted_rows"])) {
        $setting_ids = explode(",", $request["{$sortable_id}_sortable__deleted_rows"]);
        FieldTypeSettings::deleteFieldTypeSettings($setting_ids);
    }

    $request["sortable_id"] = $sortable_id;
    list($success, $message) = FieldTypeSettings::updateFieldTypeSettingOrder($request, $L);
}

$field_type_info = CoreFieldTypes::getFieldType($field_type_id);
$field_type_settings = CoreFieldTypes::getFieldTypeSettings($field_type_id);

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["sortable_id"] = $sortable_id;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_settings"] = $field_type_settings;

$module->displayPage("templates/edit.tpl", $page_vars);
