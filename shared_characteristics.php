<?php

require_once("../../global/library.php");

use FormTools\Core;
use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules;
use FormTools\Settings;
use FormTools\Modules\CustomFields\FieldTypes;

$module = Modules::initModulePage("admin");
$L = $module->getLangStrings();
$root_url = Core::getRootUrl();

$success = true;
$message = "";
if (isset($_POST["update"])) {
    list($success, $message) = FieldTypes::updateSharedCharacteristics($_POST);
}

$shared_characteristics = Settings::get("field_type_settings_shared_characteristics", "core");
$field_types = CoreFieldTypes::get(true);

$groups = explode("|", $shared_characteristics);
$grouped_characteristics = array();
$id_to_identifier = CoreFieldTypes::getFieldTypeIdToIdentifierMap();
$identifier_to_id = array_flip($id_to_identifier);

foreach ($groups as $group_info) {
    list($group_name, $group_details) = explode(":", $group_info);
    $params = explode("`", $group_details);

    $mapped = array();
    if (!empty($params)) {
        foreach ($params as $pair) {
            if (empty($pair)) {
                continue;
            }

            list($field_type_identifier, $field_type_setting_identifier) = explode(",", $pair);

            if (!array_key_exists($field_type_identifier, $identifier_to_id)) {
                continue;
            }

            $mapped[] = array(
                "field_type_identifier" => $field_type_identifier,
                "field_type_id" => $identifier_to_id[$field_type_identifier],
                "field_type_setting_identifier" => $field_type_setting_identifier
            );
        }
    }

    $grouped_characteristics[] = array(
        "group_name" => $group_name,
        "mapped" => $mapped
    );
}

$js = CoreFieldTypes::generateFieldTypeSettingsJs(array("js_key" => "identifier"));

$head_js =<<< END
$(function() {
  $(".del").live("click", function() {
    $(this).closest("tr").remove();
  });
  $(".add_row_link").bind("click", function() {
    var parent_li = $(this).closest("li");
    var group_name = parent_li.find(".group_name").val();
    var field_types_dropdown = $("#dropdown_template").html();
    var dd = create_dropdown("textbox", group_name);
    field_types_dropdown = field_types_dropdown.replace(/template_NAME/, group_name + "[]");
    var row_html = "<tr><td>" + field_types_dropdown + "</td>"
                 + "<td class=\"settings_col\">" + dd + "</td><td class=\"del\"></td></tr>";
    parent_li.find(".list_table").append(row_html);
    return false;
  });

  $(".field_type_dropdown").live("change", function() {
    var field_type_identifier = this.value;
    var group_name = $(this).closest("li").find(".group_name").val();
    var dd = create_dropdown(field_type_identifier, group_name);
    $(this).closest("tr").find(".settings_col").html(dd);
  });

  function create_dropdown(field_type_identifier, group_name) {
    var dd = "<select name=\"" + group_name + "_settings[]\" class=\"field_type_settings_dropdown\">";
    for (var i=0; i<page_ns.field_settings[field_type_identifier].length; i++) {
      var curr_setting = page_ns.field_settings[field_type_identifier][i];
      dd += "<option value=\"" + curr_setting["field_setting_identifier"] + "\">" + curr_setting["field_label"] + "</option>";
    }
    dd += "</select>";
    return dd;
  }
});

var page_ns = {};
$js
END;

$page_vars = array(
    "g_success" => $success,
    "g_message" => $message,
    "head_title" => $L["phrase_shared_characteristics"],
    "grouped_characteristics" => $grouped_characteristics,
    "js_messages" => array("word_edit", "word_delete"),
    "head_js" => $head_js,
    "css_files" => array(
        "$root_url/modules/custom_fields/css/styles.css"
    )
);

$module->displayPage("templates/shared_characteristics.tpl", $page_vars);
