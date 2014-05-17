<?php

$page_vars["prev_tabset_link"] = (!empty($links["prev_field_type_id"])) ? "edit.php?page=settings&field_type_id={$links["prev_field_type_id"]}" : "";
$page_vars["next_tabset_link"] = (!empty($links["next_field_type_id"])) ? "edit.php?page=settings&field_type_id={$links["next_field_type_id"]}" : "";

if (isset($request["add"]))
{
  list($g_success, $g_message) = cf_add_field_type_validation_rule($field_type_id, $request);
  if ($g_success)
  {
    header("location: edit.php?page=edit_validation_rule&rule_id=$g_message&new=1");
    exit;
  }
}

$field_type_info = ft_get_field_type($field_type_id, true);

// to prevent the user defining multiple rules for the same RSV validation rule (which wouldn't make sense), figure out
// what's already been created and pass them to the RSV dropdown, so they can't be selected
$existing_validation_rules = array();
foreach ($field_type_info["validation"] as $rules)
{
  $existing_validation_rules[] = $rules["rsv_rule"];
}

$page_vars["page"] = $page;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["existing_validation_rules"] = $existing_validation_rules;
$page_vars["js_messages"] = array();
$page_vars["head_string"] =<<< END
  <link type="text/css" rel="stylesheet" href="$g_root_url/modules/custom_fields/global/css/styles.css">
  <script src="$g_root_url/modules/custom_fields/global/scripts/custom_fields.js"></script>
END;

$page_vars["head_js"] =<<< END
var rules = [];
rules.push("required,rsv_rule,{$L["validation_no_validation_type"]}");
rules.push("required,rule_label,{$L["validation_no_rule_label"]}");
rules.push("required,default_error_message,{$L["validation_no_default_error_message"]}");
rules.push("if:rsv_rule=function,required,custom_function,{$L["validation_no_custom_function"]}");

$(function() {
  $("#rsv_rule").bind("change keyup", cf_ns.select_validation_rule);
});
END;

ft_display_module_page("templates/edit.tpl", $page_vars);
