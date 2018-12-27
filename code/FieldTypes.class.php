<?php

namespace FormTools\Modules\CustomFields;

use FormTools\Core;
use FormTools\ListGroups;
use FormTools\FieldSizes;
use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Settings;
use PDO;

class FieldTypes
{

	/**
	 * Creates a new field type. It either creates a brand new, blank field type, or - like other
	 * functionality in Form Tools - lets the user create a new field type with the same values
	 * as an existing field type, to cut down on configuration time.
	 *
	 * @param array $info
	 * @return integer the new field type ID
	 */
	public static function addFieldType($info)
	{
		$db = Core::$db;
		$field_sizes = FieldSizes::get();

		$field_type_name = $info["field_type_name"];
		$group_id = $info["group_id"];
		$original_field_type_id = $info["original_field_type_id"];
		$field_type_identifier = $info["field_type_identifier"];

		$num_field_types = self::getNumFieldTypes($group_id);
		$list_order = $num_field_types + 1;

		$insert_query = "
            INSERT INTO {PREFIX}field_types (is_editable, field_type_name, field_type_identifier, group_id,
                is_file_field, is_date_field, raw_field_type_map, raw_field_type_map_multi_select_id, list_order,
                compatible_field_sizes, view_field_rendering_type, view_field_php_function_source, view_field_php_function,
                view_field_smarty_markup, edit_field_smarty_markup, php_processing, resources_css,
                resources_js)
            VALUES (:is_editable, :field_type_name, :field_type_identifier, :group_id, :is_file_field, :is_date_field, 
                :raw_field_type_map, :raw_field_type_map_multi_select_id, :list_order, :compatible_field_sizes,
                :view_field_rendering_type, :view_field_php_function_source, :view_field_php_function, 
                :view_field_smarty_markup, :edit_field_smarty_markup, :php_processing, :resources_css, :resources_js)
        ";

		if (empty($original_field_type_id)) {
			$all_field_sizes = implode(",", array_keys($field_sizes));

			$db->query($insert_query);
			$db->bindAll(array(
				"is_editable" => "yes",
				"field_type_name" => $field_type_name,
				"field_type_identifier" => $field_type_identifier,
				"group_id" => $group_id,
				"is_file_field" => "no",
				"is_date_field" => "no",
				"raw_field_type_map" => "",
				"raw_field_type_map_multi_select_id" => null,
				"list_order" => $list_order,
				"compatible_field_sizes" => $all_field_sizes,
				"view_field_rendering_type" => "none",
				"view_field_php_function_source" => null,
				"view_field_php_function" => null,
				"view_field_smarty_markup" => "",
				"edit_field_smarty_markup" => "",
				"php_processing" => "",
				"resources_css" => "",
				"resources_js" => ""
			));
			$db->execute();
			$new_field_type_id = $db->getInsertId();

		} else {

			// get everything about the origin field type
			$original_info = CoreFieldTypes::getFieldType($original_field_type_id, true);

			$db->query($insert_query);
			$db->bindAll(array(
				"is_editable" => "yes",
				"field_type_name" => $field_type_name,
				"field_type_identifier" => $field_type_identifier,
				"group_id" => $group_id,
				"is_file_field" => $original_info["is_file_field"],
				"is_date_field" => $original_info["is_date_field"],
				"raw_field_type_map" => $original_info["raw_field_type_map"],
				"raw_field_type_map_multi_select_id" => (!empty($original_info["raw_field_type_map_multi_select_id"])) ?
					"'{$original_info["raw_field_type_map_multi_select_id"]}'" : null,
				"list_order" => $list_order,
				"compatible_field_sizes" => $original_info["compatible_field_sizes"],
				"view_field_rendering_type" => $original_info["view_field_rendering_type"],
				"view_field_php_function_source" => $original_info["view_field_php_function_source"],
				"view_field_php_function" => $original_info["view_field_php_function"],
				"view_field_smarty_markup" => $original_info["view_field_smarty_markup"],
				"edit_field_smarty_markup" => $original_info["edit_field_smarty_markup"],
				"php_processing" => $original_info["php_processing"],
				"resources_css" => $original_info["resources_css"],
				"resources_js" => $original_info["resources_js"]
			));
			$db->execute();
			$new_field_type_id = $db->getInsertId();

			// now add all the settings
			foreach ($original_info["settings"] as $setting_info) {
				$list_order = $setting_info["list_order"];

				$db->query("
                    INSERT INTO {PREFIX}field_type_settings (field_type_id, field_label, field_setting_identifier,
                        field_type, field_orientation, default_value_type, default_value, list_order)
                    VALUES (:field_type_id, :field_label, :field_setting_identifier, :field_type, :field_orientation,
                        :default_value_type, :default_value, :list_order)
                ");
				$db->bindAll(array(
					"field_type_id" => $new_field_type_id,
					"field_label" => $setting_info["field_label"],
					"field_setting_identifier" => $setting_info["field_setting_identifier"],
					"field_type" => $setting_info["field_type"],
					"field_orientation" => $setting_info["field_orientation"],
					"default_value_type" => $setting_info["default_value_type"],
					"default_value" => $setting_info["default_value"],
					"list_order" => $list_order
				));
				$db->execute();
				$setting_id = $db->getInsertId();

				// finally, add any options for the setting
				foreach ($setting_info["options"] as $option_info) {
					$db->query("
                        INSERT INTO {PREFIX}field_type_setting_options (setting_id, option_text, option_value, option_order, is_new_sort_group)
                        VALUES (:setting_id, :option_text, :option_value, :option_order, :is_new_sort_group)
                    ");
					$db->bindAll(array(
						"setting_id" => $setting_id,
						"option_text" => $option_info["option_text"],
						"option_value" => $option_info["option_value"],
						"option_order" => $option_info["option_order"],
						"is_new_sort_group" => $option_info["is_new_sort_group"]
					));
					$db->execute();
				}
			}

			// now add the validation
			foreach ($original_info["validation"] as $rule_info) {
				$db->query("
                    INSERT INTO {PREFIX}field_type_validation_rules (field_type_id, rsv_rule, rule_label,
                        rsv_field_name, custom_function, custom_function_required, default_error_message, list_order)
                    VALUES (:field_type_id, :rsv_rule, :rule_label, :rsv_field_name, :custom_function,
                        :custom_function_required, :default_error_message, :list_order)
                ");
				$db->bindAll(array(
					"field_type_id" => $new_field_type_id,
					"rsv_rule" => $rule_info["rsv_rule"],
					"rule_label" => $rule_info["rule_label"],
					"rsv_field_name" => $rule_info["rsv_field_name"],
					"custom_function" => $rule_info["custom_function"],
					"custom_function_required" => $rule_info["custom_function_required"],
					"default_error_message" => $rule_info["default_error_message"],
					"list_order" => $rule_info["list_order"]
				));
				$db->execute();
			}
		}

		return $new_field_type_id;
	}


	/**
	 * Deletes a field type. If this function is being called, the user has already been notified about what fields
	 * use the field type (if any), so we can go ahead and delete it safely. Any fields that used the field will be
	 * assigned to the textbox field type.
	 *
	 * @param integer $field_type_id
	 */
	public static function deleteFieldType($field_type_id, $L)
	{
		$db = Core::$db;

		if (empty($field_type_id) || !is_numeric($field_type_id)) {
			return array(false, $L["notify_cannot_delete_invalid_field_type_id"]);
		}

		$settings = CoreFieldTypes::getFieldTypeSettings($field_type_id);

		$db->query("DELETE FROM {PREFIX}field_types WHERE field_type_id = :field_type_id");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		$db->query("DELETE FROM {PREFIX}field_type_settings WHERE field_type_id = :field_type_id");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		$db->query("DELETE FROM {PREFIX}field_type_validation_rules WHERE field_type_id = :field_type_id");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		foreach ($settings as $setting_info) {
			$db->query("DELETE FROM {PREFIX}field_type_setting_options WHERE setting_id = :setting_id");
			$db->bind("setting_id", $setting_info["setting_id"]);
			$db->execute();

			$db->query("DELETE FROM {PREFIX}field_settings WHERE setting_id = :setting_id");
			$db->bind("setting_id", $setting_info["setting_id"]);
			$db->execute();
		}

		// update all fields that referenced this field type to set them as textboxes. It also assumes that the textbox
		// permits any field size, so that just resetting the type will be compatible with whatever size it was formerly
		self::resetFieldTypeUsagesToTextboxes($field_type_id);

		return array(true, $L["notify_field_type_deleted"]);
	}


	/**
	 * Called on the Edit Custom Field page: main tab.
	 *
	 * @param $field_type_id
	 * @param $info
	 */
	public static function updateMainTab($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$old_field_type_info = CoreFieldTypes::getFieldType($field_type_id);
		$old_group_id = $old_field_type_info["group_id"];
		$group_id = $info["group_id"];

		$raw_field_type_map_multi_select_id = (isset($info["raw_field_type_map_multi_select_id"]) && !empty($info["raw_field_type_map_multi_select_id"])) ?
			$info["raw_field_type_map_multi_select_id"] : null;

		// if the user just change the group, just add it to the end
		$list_order = $old_field_type_info["list_order"];
		if ($group_id != $old_group_id) {
			$num_field_types = self::getNumFieldTypes($group_id);
			$list_order = $num_field_types + 1;
		}

		$compatible_field_sizes = "";
		if (isset($info["compatible_field_sizes"])) {
			$compatible_field_sizes = implode(",", $info["compatible_field_sizes"]);
		}

		$db->query("
            UPDATE {PREFIX}field_types
            SET    field_type_name = :field_type_name,
                   compatible_field_sizes = :compatible_field_sizes,
                   group_id = :group_id,
                   is_file_field = :is_file_field,
                   is_date_field = :is_date_field,
                   raw_field_type_map = :raw_field_type_map,
                   raw_field_type_map_multi_select_id = :raw_field_type_map_multi_select_id,
                   list_order = :list_order
            WHERE  field_type_id = :field_type_id
        ");
		$db->bindAll(array(
			"field_type_name" => $info["field_type_name"],
			"compatible_field_sizes" => $compatible_field_sizes,
			"group_id" => $group_id,
			"is_file_field" => $info["is_file_field"],
			"is_date_field" => $info["is_date_field"],
			"raw_field_type_map" => $info["raw_field_type_map"],
			"raw_field_type_map_multi_select_id" => $raw_field_type_map_multi_select_id,
			"list_order" => $list_order,
			"field_type_id" => $field_type_id
		));
		$db->execute();

		if ($group_id != $old_group_id) {
			FieldTypes::sortFieldGroup($old_group_id);
		}

		return array(true, $L["notify_custom_field_updated"]);
	}


	public static function updateClientTab($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$db->query("
            UPDATE {PREFIX}field_types
            SET    view_field_rendering_type = :view_field_rendering_type,
                   view_field_php_function_source = :view_field_php_function_source,
                   view_field_php_function = :view_field_php_function,
                   view_field_smarty_markup = :view_field_smarty_markup,
                   edit_field_smarty_markup = :edit_field_smarty_markup,
                   resources_js      = :resources_js,
                   resources_css     = :resources_css
            WHERE  field_type_id = :field_type_id
        ");
		$db->bindAll(array(
			"view_field_rendering_type" => $info["rendering_type"],
			"view_field_php_function_source" => $info["view_field_php_function_source"],
			"view_field_php_function" => $info["function_name"],
			"view_field_smarty_markup" => $info["view_field_smarty_markup"],
			"edit_field_smarty_markup" => $info["edit_field_smarty_markup"],
			"resources_js" => $info["resources_js"],
			"resources_css" => $info["resources_css"],
			"field_type_id" => $field_type_id
		));
		$db->execute();

		return array(true, $L["notify_custom_field_updated"]);
	}


	/**
	 * Updates the list of validation rules for a particular field type. Note: this function
	 * does NOT delete any validation rules that have been assigned to any actual field. The reason being,
	 * it's possible that that admin just deleted a field, then will recreate it now. This prevent accidentally
	 * deleting all validation rules assigned to the fields. The fact that those records are orphaned is no big
	 * deal (albeit inelegant). Whenever the field is updated via the Edit Field dialog, the old validation rules
	 * will be automatically removed.
	 *
	 * @param integer $field_type_id
	 * @param array $info
	 */
	public static function updateValidationTab($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$sortable_id = $info["sortable_id"];
		$rows = explode(",", $info["{$sortable_id}_sortable__rows"]);

		$db->query("DELETE FROM {PREFIX}field_type_validation_rules WHERE field_type_id = :field_type_id");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		$order = 1;
		foreach ($rows as $row) {
			if (empty($row) || !is_numeric($row)) {
				continue;
			}

			if (empty($rule)) {
				continue;
			}

			$db->query("
                INSERT INTO {PREFIX}field_type_validation_rules (field_type_id, rsv_rule, rule_label, custom_function,
                    default_error_message, list_order)
                VALUES (:field_type_id, :rsv_rule, :rule_label, :custom_function, :default_error_message, :list_order)
            ");
			$db->bindAll(array(
				"field_type_id" => $field_type_id,
				"rsv_rule" => $info["rsv_rule_{$row}"],
				"rule_label" => $info["label_{$row}"],
				"custom_function" => $info["custom_function_{$row}"],
				"default_error_message" => $info["default_error_message_{$row}"],
				"list_order" => $order
			));
			$db->execute();

			$order++;
		}

		return array(true, $L["notify_field_type_validation_updated"]);
	}


	public static function updateServerTab($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$db->query("
            UPDATE {PREFIX}field_types
            SET    php_processing = :php_processing
            WHERE  field_type_id = :field_type_id
        ");
		$db->bindAll(array(
			"php_processing" => $info["php_processing"],
			"field_type_id" => $field_type_id
		));
		$db->execute();

		return array(true, $L["notify_custom_field_updated"]);
	}


	/**
	 * Called on the main page. This updates the custom field orders, grouping and group names. It also
	 * deletes groups, if requested by the user.
	 *
	 * @param $info the post request
	 */
	public static function updateCustomFields($info, $L)
	{
		$db = Core::$db;

		$sortable_id = $info["sortable_id"];

		// perhaps this entire thing could get moved to a helper function...?
		$grouped_info = explode("~", $info["{$sortable_id}_sortable__rows"]);

		$new_group_order = 1;
		foreach ($grouped_info as $curr_grouped_info) {
			list($curr_group_id, $ordered_field_type_ids_str) = explode("|", $curr_grouped_info);
			$ordered_field_type_ids = explode(",", $ordered_field_type_ids_str);

			$db->query("
                UPDATE {PREFIX}list_groups
                SET    list_order = :list_order
                WHERE  group_id = :group_id
            ");
			$db->bindAll(array(
				"list_order" => $new_group_order,
				"group_id" => $curr_group_id
			));
			$db->execute();

			$new_field_type_order = 1;

			foreach ($ordered_field_type_ids as $field_type_id) {
				if (empty($field_type_id)) {
					continue;
				}

				$db->query("
                    UPDATE {PREFIX}field_types
                    SET    group_id = :group_id,
                           list_order = :list_order
                    WHERE  field_type_id = :field_type_id
                ");
				$db->bindAll(array(
					"group_id" => $curr_group_id,
					"list_order" => $new_field_type_order,
					"field_type_id" => $field_type_id
				));
				$db->execute();

				$new_field_type_order++;
			}

			// now update the group name
			$db->query("
                UPDATE {PREFIX}list_groups
                SET    group_name = :group_name
                WHERE  group_id = :group_id
            ");
			$db->bindAll(array(
				"group_name" => $info["group_name_{$curr_group_id}"],
				"group_id" => $curr_group_id
			));
			$db->execute();

			$new_group_order++;
		}

		// update the enabled/disabled list
		self::updateEnabledFieldTypes($info["enabled_field_types"]);

		if (isset($info["{$sortable_id}_sortable__delete_group"])) {
			ListGroups::deleteListGroup($info["{$sortable_id}_sortable__delete_group"]);
		}

		return array(true, $L["notify_custom_fields_updated"]);
	}


	public static function getNumFieldTypes($group_id)
	{
		$db = Core::$db;

		$db->query("
            SELECT count(*)
            FROM   {PREFIX}field_types
            WHERE  group_id = :group_id
        ");
		$db->bind("group_id", $group_id);
		$db->execute();

		return $db->fetch(PDO::FETCH_COLUMN);
	}


	/**
	 * This re-sorts all field types in a group, to ensure sequential ordering.
	 *
	 * @param integer $group_id
	 */
	public static function sortFieldGroup($group_id)
	{
		$db = Core::$db;

		$db->query("
            SELECT field_type_id
            FROM   {PREFIX}field_types
            WHERE  group_id = $group_id
            ORDER BY list_order
        ");
		$db->bind("group_id", $group_id);
		$db->execute();
		$field_type_ids = $db->fetchAll(PDO::FETCH_COLUMN);

		$new_order = 1;
		foreach ($field_type_ids as $field_type_id) {
			$db->query("
                UPDATE {PREFIX}field_types
                SET    list_order = :list_order
                WHERE  field_type_id = :field_type_id
            ");
			$db->bindAll(array(
				"list_order" => $new_order,
				"field_type_id" => $field_type_id
			));
			$db->execute();
			$new_order++;
		}
	}


	/**
	 * This retrieves the previous and next field_type_id, as determined by the sort order.
	 *
	 * @param integer $form_id
	 * @return array prev_field_type_id => the previous account ID (or empty string)
	 *               next_field_type_id => the next account ID (or empty string)
	 */
	public static function getFieldTypePrevNextLinks($field_type_id)
	{
		$field_types = CoreFieldTypes::get();

		$ordered_field_type_ids = array();
		foreach ($field_types as $field_type_info) {
			$ordered_field_type_ids[] = $field_type_info["field_type_id"];
		}

		$current_index = array_search($field_type_id, $ordered_field_type_ids);

		$return_info = array("prev_field_type_id" => "", "next_field_type_id" => "");
		if ($current_index === 0) {
			if (count($ordered_field_type_ids) > 1) {
				$return_info["next_field_type_id"] = $ordered_field_type_ids[$current_index + 1];
			}
		} else {
			if ($current_index === count($ordered_field_type_ids) - 1) {
				if (count($ordered_field_type_ids) > 1) {
					$return_info["prev_field_type_id"] = $ordered_field_type_ids[$current_index - 1];
				}
			} else {
				$return_info["prev_field_type_id"] = $ordered_field_type_ids[$current_index - 1];
				$return_info["next_field_type_id"] = $ordered_field_type_ids[$current_index + 1];
			}
		}

		return $return_info;
	}


	public static function updateSharedCharacteristics($info)
	{
		$group_strs = array();
		foreach ($info["all_group_names"] as $group_name) {
			$row_str = "";

			// this prevent the core groups actually being lost if the user deletes all contents
			if (!isset($info[$group_name])) {
				$info[$group_name] = array();
				$info["{$group_name}_settings"] = array();
			}

			$settings = array();
			for ($i = 0; $i < count($info[$group_name]); $i++) {
				$settings[] = $info[$group_name][$i] . "," . $info["{$group_name}_settings"][$i];
			}
			$row_str .= "$group_name:" . implode("`", $settings);
			$group_strs[] = $row_str;
		}

		$str = implode("|", $group_strs);

		Settings::set(array("field_type_settings_shared_characteristics" => $str));

		return array(true, "The shared characteristics have been updated.");
	}


	public static function updateEnabledFieldTypes($field_type_ids)
	{
		$db = Core::$db;

		$db->query("UPDATE {PREFIX}field_types SET is_enabled = 'no'");
		$db->execute();

		$update_clause = "";
		if (!empty($field_type_ids)) {
			$update_clause = "OR field_type_id IN (" . implode(", ", $field_type_ids) . ")";
		}

		// the Core textbox and date fields are ALWAYS enabled
		$db->query("
			UPDATE {PREFIX}field_types
			SET   is_enabled = 'yes'
			WHERE field_type_identifier = 'textbox' OR
			      field_type_identifier = 'date'
			  	  $update_clause
		");
		$db->execute();
	}

	public static function resetFieldTypeUsagesToTextboxes($field_type_id)
	{
		$db = Core::$db;

		$textbox_field_type_id = CoreFieldTypes::getFieldTypeIdByIdentifier("textbox");

		$db->query("SELECT field_id FROM {PREFIX}form_fields WHERE field_type_id = :field_type_id");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();
		$field_ids = $db->fetchAll(PDO::FETCH_COLUMN);

		if (!empty($field_ids)) {
			$field_id_str = implode(",", $field_ids);
			$db->query("DELETE FROM {PREFIX}field_validation WHERE field_id IN ($field_id_str)");
			$db->execute();

			$db->query("
                UPDATE {PREFIX}form_fields
                SET field_type_id = :textbox_field_type_id
                WHERE field_type_id = :field_type_id
            ");
			$db->bind("textbox_field_type_id", $textbox_field_type_id);
			$db->bind("field_type_id", $field_type_id);
			$db->execute();
		}
	}
}
