<?php

use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules;
use FormTools\Modules\CustomFields\FieldTypes;
use FormTools\Sessions;

if (isset($request["update"])) {
    list($success, $message) = FieldTypes::updateClientTab($field_type_id, $request, $L);
}

$field_type_info = CoreFieldTypes::getFieldType($field_type_id);
$field_type_settings = CoreFieldTypes::getFieldTypeSettings($field_type_id);
$modules = Modules::getList();

$page_vars["g_success"] = $success;
$page_vars["g_message"] = $message;
$page_vars["page"] = $page;
$page_vars["modules"] = $modules;
$page_vars["current_inner_tab"] = Sessions::getWithFallback("inner_tabs.custom_fields_edit_field_displaying", 1);
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_settings"] = $field_type_settings;
$page_vars["head_js"] =<<< END
$(function() {
    var onChangeTab = function (tabNum) {
        if (tabNum === 1) {
            view_field_smarty_markup_field.refresh();
        } else if (tabNum === 2) {
            edit_field_markup_field.refresh();
        } else if (tabNum === 3) {
            include_css_field.refresh();
        } else if (tabNum === 4) {
            include_js_field.refresh();
        }
    };
    
    ft.init_inner_tabs(onChangeTab);
    $("input[name=rendering_type]").bind("click", function() {
        if (this.value == "smarty") {
            $("#view_field_smarty_markup_section").show();
            view_field_smarty_markup_field.refresh();
        } else {
            $("#view_field_smarty_markup_section").hide();
        }
    });
});
END;

$module->displayPage("templates/edit.tpl", $page_vars);
