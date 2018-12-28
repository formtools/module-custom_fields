<?php

namespace FormTools\Modules\CustomFields;

use FormTools\Core;
use FormTools\Settings;
use PDO, Exception;


class FieldTypeSettings
{
	/**
	 * Adds a new field type. Looks like there's duplication here with the Core function with the same name in the
	 * FieldTypes class.
	 *
	 * @param integer $field_type_id
	 * @param array $info
	 */
	public static function addFieldTypeSetting($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$default_value_type = $info["default_value_type"];
		if ($default_value_type == "static") {
			$default_value = $info["default_value_static"];
		} else {
			$default_value = $info["default_value_dynamic"];
		}

		$sortable_id = $info["sortable_id"];

		$field_type = $info["field_type"];
		$field_orientation = "na";
		if ($field_type == "radios" || $field_type == "checkboxes") {
			$field_orientation = $info["field_orientation"];
		}

		$num_field_type_settings = self::getNumFieldTypeSettings($field_type_id);
		$list_order = $num_field_type_settings + 1;

		try {
			$db->query("
                INSERT INTO {PREFIX}field_type_settings (field_type_id, field_label, field_setting_identifier,
                  field_type, field_orientation, default_value_type, default_value, list_order)
                VALUES (:field_type_id, :field_label, :field_setting_identifier, :field_type,
                  :field_orientation, :default_value_type, :default_value, :list_order)
            ");
			$db->bindAll(array(
				"field_type_id" => $field_type_id,
				"field_label" => $info["field_label"],
				"field_setting_identifier" => $info["field_setting_identifier"],
				"field_type" => $field_type,
				"field_orientation" => $field_orientation,
				"default_value_type" => $default_value_type,
				"default_value" => $default_value,
				"list_order" => $list_order
			));
			$db->execute();

			$setting_id = $db->getInsertId();

			if (in_array($field_type, array("radios", "checkboxes", "select", "multi-select"))) {
				$sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);
				$sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

				$new_row_count = 1;
				foreach ($sortable_rows as $row_number) {
					$is_new_sort_group = in_array($row_number, $sortable_new_groups) ? "yes" : "no";

					$db->query("
                        INSERT INTO {PREFIX}field_type_setting_options (setting_id, option_text, option_value, option_order, is_new_sort_group)
                        VALUES (:setting_id, :option_text, :option_value, :new_row_count, :is_new_sort_group)
                    ");
					$db->bindAll(array(
						"setting_id" => $setting_id,
						"option_text" => $info["option_text_{$row_number}"],
						"option_value" => $info["option_value_{$row_number}"],
						"option_order" => $new_row_count,
						"is_new_sort_group" => $is_new_sort_group
					));
					$db->execute();
					$new_row_count++;
				}
			}
		} catch (Exception $e) {
			return array(false, $L["notify_problem_adding_field_type_setting"]);
		}

		return array(true, $setting_id);
	}


	/**
	 * Called on the Update Field Type Setting page.
	 *
	 * @param integer $setting_id
	 * @param integer $info the POST request containing the standard form fields & the contents of the sortable
	 *                      option list.
	 */
	public static function updateFieldTypeSetting($setting_id, $info, $L)
	{
		$db = Core::$db;

		$field_type = $info["field_type"];

		$default_value_type = $info["default_value_type"];
		if ($default_value_type == "static") {
			$default_value = $info["default_value_static"];
		} else {
			$default_value = $info["default_value_dynamic"];
		}

		$sortable_id = $info["sortable_id"];

		$field_orientation = "na";
		if ($field_type == "radios" || $field_type == "checkboxes") {
			$field_orientation = $info["field_orientation"];
		}

		try {
			$db->query("
                UPDATE {PREFIX}field_type_settings
                SET    field_label = :field_label,
                       field_type = :field_type,
                       field_orientation = :field_orientation,
                       default_value_type = :default_value_type,
                       default_value = :default_value
                WHERE  setting_id = :setting_id
            ");
			$db->bindAll(array(
				"field_label" => $info["field_label"],
				"field_type" => $field_type,
				"field_orientation" => $field_orientation,
				"default_value_type" => $default_value_type,
				"default_value" => $default_value,
				"setting_id" => $setting_id
			));
			$db->execute();
		} catch (Exception $e) {
			return array(false, $L["notify_problem_updating_field_type_setting"]);
		}

		// now update the options
		$sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);
		$sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

		$db->query("DELETE FROM {PREFIX}field_type_setting_options WHERE setting_id = :setting_id");
		$db->bind("setting_id", $setting_id);
		$db->execute();

		if (in_array($field_type, array("radios", "checkboxes", "select", "multi-select"))) {
			$new_row_count = 1;
			foreach ($sortable_rows as $row_number) {
				$is_new_sort_group = in_array($row_number, $sortable_new_groups) ? "yes" : "no";

				$db->query("
                    INSERT INTO {PREFIX}field_type_setting_options (setting_id, option_text, option_value, option_order, is_new_sort_group)
                    VALUES (:setting_id, :option_text, :option_value, :option_order, :is_new_sort_group)
                ");
				$db->bindAll(array(
					"setting_id" => $setting_id,
					"option_text" => $info["option_text_{$row_number}"],
					"option_value" => $info["option_value_{$row_number}"],
					"option_order" => $new_row_count,
					"is_new_sort_group" => $is_new_sort_group
				));
				$db->execute();
				$new_row_count++;
			}
		}

		return array(true, $L["notify_field_type_setting_updated"]);
	}


	/**
	 * Called on the Customizable Settings page.
	 *
	 * @param integer $field_type_id
	 * @param array $info
	 */
	public static function updateFieldTypeSettingOrder($info, $L)
	{
		$db = Core::$db;

		$sortable_id = $info["sortable_id"];
		$sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);

		$new_order = 1;
		foreach ($sortable_rows as $setting_id) {
			$db->query("
                UPDATE {PREFIX}field_type_settings
                SET    list_order = :list_order
                WHERE  setting_id = :setting_id
                LIMIT 1
            ");
			$db->bindAll(array(
				"list_order" => $new_order,
				"setting_id" => $setting_id
			));
			$db->execute();
			$new_order++;
		}

		return array(true, $L["notify_field_type_settings_updated"]);
	}


	public static function getNumFieldTypeSettings($field_type_id)
	{
		$db = Core::$db;

		$db->query("
            SELECT count(*)
            FROM   {PREFIX}field_type_settings
            WHERE  field_type_id = :field_type_id
        ");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		return $db->fetch(PDO::FETCH_COLUMN);
	}


	public static function updateSharedResources($info, $L)
	{
		$resources = implode("|", $info["resources"]);

		$settings = array(
			"edit_submission_shared_resources_js" => $info["edit_submission_shared_resources_js"],
			"edit_submission_shared_resources_css" => $info["edit_submission_shared_resources_css"],
			"edit_submission_onload_resources" => $resources
		);

		Settings::set($settings, "core");

		return array(true, $L["notify_shared_resources_updated"]);
	}

}
