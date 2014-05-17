<?php


function cf_get_validation_rule($rule_id)
{
  global $g_table_prefix;
  $query = mysql_query("SELECT * FROM {$g_table_prefix}field_type_validation_rules WHERE rule_id = $rule_id");
  return mysql_fetch_assoc($query);
}


function cf_update_validation_rule($rule_id, $info)
{
  global $g_table_prefix, $L;

  $info = ft_sanitize($info);

  $rsv_rule = $info["rsv_rule"];
  $rule_label = $info["rule_label"];
  $rsv_field_name = $info["rsv_field_name"];
  $default_error_message = $info["default_error_message"];
  $custom_function = $info["custom_function"];
  $custom_function_required = (isset($info["custom_function_required"])) ? $info["custom_function_required"] : "na";

  $query = mysql_query("
    UPDATE {$g_table_prefix}field_type_validation_rules
    SET    rsv_rule = '$rsv_rule',
           rule_label = '$rule_label',
           rsv_field_name = '$rsv_field_name',
           custom_function = '$custom_function',
           custom_function_required = '$custom_function_required',
           default_error_message = '$default_error_message'
    WHERE  rule_id = $rule_id
  ");

  if ($query)
  {
  	return array(true, $L["notify_validation_rule_updated"]);
  }
  else
  {
  	return array(false, $L["notify_validation_rule_not_updated"]);
  }
}


/**
 * Adds a new validation rule for a particular field type.
 *
 * @param integer $field_type_id
 * @param array $info
 */
function cf_add_field_type_validation_rule($field_type_id, $info)
{
  global $g_table_prefix, $L;

  $info = ft_sanitize($info);

  $rsv_rule   = $info["rsv_rule"];
  $rule_label = $info["rule_label"];
  $rsv_field_name = $info["rsv_field_name"];
  $default_error_message = $info["default_error_message"];
  $custom_function = $info["custom_function"];
  $custom_function_required = (isset($info["custom_function_required"])) ? $info["custom_function_required"] : "na";

  $next_order = 0;
  $count_query = mysql_query("SELECT count(*) as c FROM {$g_table_prefix}field_type_validation_rules WHERE field_type_id = $field_type_id");
  $count_result = mysql_fetch_assoc($count_query);
  $next_order = $count_result["c"] + 1;

  $query = mysql_query("
    INSERT INTO {$g_table_prefix}field_type_validation_rules (field_type_id, rsv_rule, rule_label, rsv_field_name, custom_function,
      custom_function_required, default_error_message, list_order)
    VALUES ($field_type_id, '$rsv_rule', '$rule_label', '$rsv_field_name', '$custom_function', '$custom_function_required',
      '$default_error_message', $next_order)
  ");

  if ($query)
  {
  	return array(true, mysql_insert_id());
  }
  else
  {
    return array(false, $L["notify_validation_rule_not_added"]);
  }
}


/**
 * This deletes a single validation rule for a field type. The user will have been notified
 * that it will delete the rule from all fields that reference it.
 *
 * @param integer $field_type_id
 * @param integer $rule_id
 */
function cf_delete_validation_rule($field_type_id, $rule_id)
{
  global $g_table_prefix, $L;

  mysql_query("DELETE FROM {$g_table_prefix}field_type_validation_rules WHERE rule_id = $rule_id");
  mysql_query("DELETE FROM {$g_table_prefix}field_validation WHERE rule_id = $rule_id");

  return array(true, $L["notify_validation_rule_deleted"]);
}


function cf_update_validation_rule_order($field_type_id, $info)
{
  global $g_table_prefix, $L;

  $sortable_id = $info["sortable_id"];
  $sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);

  $new_order = 1;
  foreach ($sortable_rows as $rule_id)
  {
    mysql_query("
      UPDATE {$g_table_prefix}field_type_validation_rules
      SET    list_order = $new_order
      WHERE  rule_id = $rule_id
      LIMIT 1
    ");
    $new_order++;
  }

  return array(true, $L["notify_field_type_settings_updated"]);
}

