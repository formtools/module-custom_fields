<?php

require_once("../../../global/library.php");

use FormTools\Core;
use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\General;
use FormTools\ListGroups;
use FormTools\Modules\CustomFields\FieldTypes;
use FormTools\Modules;

$module = Modules::initModulePage("admin");
$LANG = Core::$L;

switch ($request["action"]) {
    case "add_field":
        echo FieldTypes::addFieldType($request);
        break;

    case "create_new_group":
        $group_type = "field_types";
        $group_name = $request["group_name"];
        $info = ListGroups::addListGroup($group_type, $group_name);
        echo json_encode($info);
        break;

    case "get_field_type_usage":
        $field_type_id = $request["field_type_id"];
        $usage = CoreFieldTypes::getFieldTypeUsage($field_type_id);
        echo json_encode($usage);
        break;

    case "get_undeletable_field_type_info":
        $field_type_id = $request["field_type_id"];
        $field_type_info = CoreFieldTypes::getFieldType($field_type_id);
        $non_editable_info = $field_type_info["non_editable_info"];
        echo General::evalSmartyString($non_editable_info);
        break;
}
