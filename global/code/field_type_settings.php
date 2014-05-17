<?php


/**
 * Adds a new field type.
 *
 * @param integer $field_type_id
 * @param array $info
 */
function cf_add_field_type_setting($field_type_id, $info)
{
  global $g_table_prefix, $L;

  $info = ft_sanitize($info);
  $field_label   = $info["field_label"];
  $field_setting_identifier = $info["field_setting_identifier"];
  $field_type    = $info["field_type"];

  $default_value_type = $info["default_value_type"];
  if ($default_value_type == "static")
    $default_value = $info["default_value_static"];
  else
    $default_value = $info["default_value_dynamic"];

  $sortable_id   = $info["sortable_id"];

  $field_orientation = "na";
  if ($field_type == "radios" || $field_type == "checkboxes")
    $field_orientation = $info["field_orientation"];

  $num_field_type_settings = _cf_get_num_field_type_settings($field_type_id);
  $list_order = $num_field_type_settings + 1;

  $query = mysql_query("
    INSERT INTO {$g_table_prefix}field_type_settings (field_type_id, field_label, field_setting_identifier,
      field_type, field_orientation, default_value_type, default_value, list_order)
    VALUES ($field_type_id, '$field_label', '$field_setting_identifier', '$field_type',
      '$field_orientation', '$default_value_type', '$default_value', $list_order)
  ");

  if ($query)
  {
    $setting_id = mysql_insert_id();

    if (in_array($field_type, array("radios", "checkboxes", "select", "multi-select")))
    {
      $sortable_rows       = explode(",", $info["{$sortable_id}_sortable__rows"]);
      $sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

      $new_row_count = 1;
      foreach ($sortable_rows as $row_number)
      {
        $option_value = $info["option_value_{$row_number}"];
        $option_text  = $info["option_text_{$row_number}"];
        $is_new_sort_group = in_array($row_number, $sortable_new_groups) ? "yes" : "no";

        mysql_query("
          INSERT INTO {$g_table_prefix}field_type_setting_options (setting_id, option_text, option_value,
            option_order, is_new_sort_group)
          VALUES ($setting_id, '$option_text', '$option_value', '$new_row_count', '$is_new_sort_group')
        ");
        $new_row_count++;
      }
    }

    return array(true, $setting_id);
  }

  return array(false, $L["notify_problem_adding_field_type_setting"]);
}


/**
 * Called on the Update Field Type Setting page.
 *
 * @param integer $setting_id
 * @param integer $info the POST request containing the standard form fields & the contents of the sortable
 *                      option list.
 */
function cf_update_field_type_setting($setting_id, $info)
{
  global $g_table_prefix, $L;

  // if all goes well, this is what we return. Let's start optimistically...
  $success = true;
  $message = $L["notify_field_type_setting_updated"];

  $info = ft_sanitize($info);
  $field_label   = $info["field_label"];
  $field_type    = $info["field_type"];

  $default_value_type = $info["default_value_type"];
  if ($default_value_type == "static")
    $default_value = $info["default_value_static"];
  else
    $default_value = $info["default_value_dynamic"];

  $field_type_id = $info["field_type_id"];
  $sortable_id   = $info["sortable_id"];

  $num_field_type_settings = _cf_get_num_field_type_settings($field_type_id);
  $list_order = $num_field_type_settings + 1;

  $field_orientation = "na";
  if ($field_type == "radios" || $field_type == "checkboxes")
    $field_orientation = $info["field_orientation"];

  $query = mysql_query("
    UPDATE {$g_table_prefix}field_type_settings
    SET    field_label = '$field_label',
           field_type = '$field_type',
           field_orientation = '$field_orientation',
           default_value_type = '$default_value_type',
           default_value = '$default_value'
    WHERE  setting_id = $setting_id
  ");

  if (!$query)
  {
    $success = false;
    $message = $L["notify_problem_updating_field_type_setting"];
  }

  // now update the options
  $sortable_rows       = explode(",", $info["{$sortable_id}_sortable__rows"]);
  $sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

  mysql_query("DELETE FROM {$g_table_prefix}field_type_setting_options WHERE setting_id = $setting_id");

  if (in_array($field_type, array("radios", "checkboxes", "select", "multi-select")))
  {
    $new_row_count = 1;
    foreach ($sortable_rows as $row_number)
    {
      $option_value = $info["option_value_{$row_number}"];
      $option_text  = $info["option_text_{$row_number}"];
      $is_new_sort_group = in_array($row_number, $sortable_new_groups) ? "yes" : "no";

      mysql_query("
        INSERT INTO {$g_table_prefix}field_type_setting_options (setting_id, option_text, option_value,
          option_order, is_new_sort_group)
        VALUES ($setting_id, '$option_text', '$option_value', '$new_row_count', '$is_new_sort_group')
      ");
      $new_row_count++;
    }
  }

  return array($success, $message);
}


/**
 * Called on the Customizable Settings page.
 *
 * @param integer $field_type_id
 * @param array $info
 */
function cf_update_field_type_setting_order($field_type_id, $info)
{
  global $g_table_prefix, $L;

  $sortable_id = $info["sortable_id"];
  $sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);

  $new_order = 1;
  foreach ($sortable_rows as $setting_id)
  {
    mysql_query("
      UPDATE {$g_table_prefix}field_type_settings
      SET    list_order = $new_order
      WHERE  setting_id = $setting_id
      LIMIT 1
    ");
    $new_order++;
  }

  return array(true, $L["notify_field_type_settings_updated"]);
}


/**
 * Deletes a field type setting. It also updates all existing form fields that were referencing
 * this setting to remove the dependant data.
 *
 * @param integer $field_type_id
 * @param string $setting_id_list comma delimited list of setting IDs
 */
function cf_delete_field_type_settings($field_type_id, $setting_id_list)
{
  global $g_table_prefix;

  $setting_ids = explode(",", $setting_id_list);

  foreach ($setting_ids as $setting_id)
  {
    if (empty($setting_id) || !is_numeric($setting_id))
      continue;

    mysql_query("DELETE FROM {$g_table_prefix}field_settings WHERE setting_id = $setting_id");
    mysql_query("DELETE FROM {$g_table_prefix}field_type_settings WHERE setting_id = $setting_id");
    mysql_query("DELETE FROM {$g_table_prefix}field_type_setting_options WHERE setting_id = $setting_id");
  }
}


function _cf_get_num_field_type_settings($field_type_id)
{
  global $g_table_prefix;

  $query = mysql_query("
    SELECT count(*) as c
    FROM   {$g_table_prefix}field_type_settings
    WHERE  field_type_id = $field_type_id
  ");
  $result = mysql_fetch_assoc($query);

  return $result["c"];
}


function cf_update_shared_resources($info)
{
  global $L;

  $resources = implode("|", $info["resources"]);

  $settings = array(
    "edit_submission_shared_resources_js"  => $info["edit_submission_shared_resources_js"],
    "edit_submission_shared_resources_css" => $info["edit_submission_shared_resources_css"],
    "edit_submission_onload_resources"     => $resources
  );

  ft_set_settings($settings, "core");

  return array(true, $L["notify_shared_resources_updated"]);
}
