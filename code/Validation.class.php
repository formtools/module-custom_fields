<?php

namespace FormTools\Modules\CustomFields;

use FormTools\Core;
use PDO, Exception;


class Validation
{
	public static function getValidationRule($rule_id)
	{
		$db = Core::$db;

		$db->query("SELECT * FROM {PREFIX}field_type_validation_rules WHERE rule_id = :rule_id");
		$db->bind("rule_id", $rule_id);
		$db->execute();

		return $db->fetch();
	}


	public static function updateValidationRule($rule_id, $info, $L)
	{
		$db = Core::$db;

		try {
			$db->query("
                UPDATE {PREFIX}field_type_validation_rules
                SET    rsv_rule = :rsv_rule,
                       rule_label = :rule_label,
                       rsv_field_name = :rsv_field_name,
                       custom_function = :custom_function,
                       custom_function_required = :custom_function_required,
                       default_error_message = :default_error_message
                WHERE  rule_id = :rule_id
            ");
			$db->bindAll(array(
				"rsv_rule" => $info["rsv_rule"],
				"rule_label" => $info["rule_label"],
				"rsv_field_name" => $info["rsv_field_name"],
				"custom_function" => $info["custom_function"],
				"custom_function_required" => (isset($info["custom_function_required"])) ? $info["custom_function_required"] : "na",
				"default_error_message" => $info["default_error_message"],
				"rule_id" => $rule_id
			));
			$db->execute();
		} catch (Exception $e) {
			return array(false, $L["notify_validation_rule_not_updated"]);
		}

		return array(true, $L["notify_validation_rule_updated"]);
	}


	/**
	 * Adds a new validation rule for a particular field type.
	 *
	 * @param integer $field_type_id
	 * @param array $info
	 */
	public static function addFieldTypeValidationRule($field_type_id, $info, $L)
	{
		$db = Core::$db;

		$db->query("
            SELECT count(*)
            FROM {PREFIX}field_type_validation_rules
            WHERE field_type_id = :field_type_id
        ");
		$db->bind("field_type_id", $field_type_id);
		$db->execute();

		$next_order = $db->fetch(PDO::FETCH_COLUMN) + 1;

		try {
			$db->query("
                INSERT INTO {PREFIX}field_type_validation_rules (field_type_id, rsv_rule, rule_label, rsv_field_name, custom_function,
                    custom_function_required, default_error_message, list_order)
                VALUES (:field_type_id, :rsv_rule, :rule_label, :rsv_field_name, :custom_function, :custom_function_required,
                    :default_error_message, :list_order)
            ");
			$db->bindAll(array(
				"field_type_id" => $field_type_id,
				"rsv_rule" => $info["rsv_rule"],
				"rule_label" => $info["rule_label"],
				"rsv_field_name" => $info["rsv_field_name"],
				"custom_function" => $info["custom_function"],
				"custom_function_required" => (isset($info["custom_function_required"])) ? $info["custom_function_required"] : "na",
				"default_error_message" => $info["default_error_message"],
				"list_order" => $next_order
			));
			$db->execute();
		} catch (Exception $e) {
			return array(false, $L["notify_validation_rule_not_added"]);
		}

		return array(true, $db->getInsertId());
	}


	/**
	 * This deletes a single validation rule for a field type. The user will have been notified
	 * that it will delete the rule from all fields that reference it.
	 *
	 * @param integer $field_type_id
	 * @param integer $rule_id
	 */
	public static function deleteValidationRule($rule_id, $L)
	{
		$db = Core::$db;

		$db->query("DELETE FROM {PREFIX}field_type_validation_rules WHERE rule_id = :rule_id");
		$db->bind("rule_id", $rule_id);
		$db->execute();

		$db->query("DELETE FROM {PREFIX}field_validation WHERE rule_id = :rule_id");
		$db->bind("rule_id", $rule_id);
		$db->execute();

		return array(true, $L["notify_validation_rule_deleted"]);
	}


	public static function updateValidationRuleOrder($info, $L)
	{
		$db = Core::$db;

		$sortable_id = $info["sortable_id"];
		$sortable_rows = explode(",", $info["{$sortable_id}_sortable__rows"]);

		$new_order = 1;
		foreach ($sortable_rows as $rule_id) {
			$db->query("
                UPDATE {PREFIX}field_type_validation_rules
                SET    list_order = :list_order
                WHERE  rule_id = :rule_id
                LIMIT 1
            ");
			$db->bindAll(array(
				"list_order" => $new_order,
				"rule_id" => $rule_id
			));
			$db->execute();

			$new_order++;
		}

		return array(true, $L["notify_field_type_settings_updated"]);
	}
}
