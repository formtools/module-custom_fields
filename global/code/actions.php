<?php

require_once("../../../../global/session_start.php");
ft_check_permission("admin");
require_once("../../library.php");

// the action to take and the ID of the page where it will be displayed (allows for
// multiple calls on same page to load content in unique areas)
$request = array_merge($_GET, $_POST);
$action  = $request["action"];

// Find out if we need to return anything back with the response. This mechanism allows us to pass any information
// between the Ajax submit function and the Ajax return function. Usage:
//   "return_vals[]=question1:answer1&return_vals[]=question2:answer2&..."
$return_val_str = "";
if (isset($request["return_vals"]))
{
  $vals = array();
  foreach ($request["return_vals"] as $pair)
  {
    list($key, $value) = split(":", $pair);
    $vals[] = "\"$key\": \"$value\"";
  }
  $return_val_str = ", " . join(", ", $vals);
}


switch ($action)
{
  case "add_field":
  	echo cf_add_field_type($request);
    break;

  case "create_new_group":
  	$group_type = "field_types";
  	$group_name = $request["group_name"];
  	$info = ft_add_list_group($group_type, $group_name);
  	echo ft_convert_to_json($info);
  	break;

  case "get_field_type_usage":
  	$field_type_id = $request["field_type_id"];
  	$usage = ft_get_field_type_usage($field_type_id);
  	echo ft_convert_to_json($usage);
  	break;

  case "get_undeletable_field_type_info":
  	$field_type_id = $request["field_type_id"];
    $field_type_info = ft_get_field_type($field_type_id);
    $non_editable_info = $field_type_info["non_editable_info"];

    // TODO. This will need to be expanded to add in the language files from the appropriate module
    echo ft_eval_smarty_string($non_editable_info);
  	break;
}