<?php

$sortable_id = "field_type_setting_options";
$setting_id = ft_load_module_field("custom_fields", "setting_id", "setting_id");

$page_vars["prev_tabset_link"] = (!empty($links["prev_field_type_id"])) ? "edit.php?page=settings&field_type_id={$links["prev_field_type_id"]}" : "";
$page_vars["next_tabset_link"] = (!empty($links["next_field_type_id"])) ? "edit.php?page=settings&field_type_id={$links["next_field_type_id"]}" : "";

if (isset($_GET["new"]))
{
  $g_success = true;
  $g_message = $L["notify_field_type_setting_added"];
}

if (isset($request["update"]))
{
  $request["field_type_id"] = $field_type_id;
  $request["sortable_id"] = $sortable_id;
  list($g_success, $g_message) = cf_update_field_type_setting($setting_id, $request);
}

$field_type_info    = ft_get_field_type($field_type_id);
$field_type_setting = ft_get_field_type_setting($setting_id);

// find out if we need to init the options table to have a default row
$num_options = $field_type_setting["options"];
$init_options_js = ($num_options == 0) ? "cf_ns.add_setting_option();" : "";

$page_vars["page"] = $page;
$page_vars["sortable_id"] = $sortable_id;
$page_vars["field_type_info"] = $field_type_info;
$page_vars["field_type_setting"] = $field_type_setting;
$page_vars["js_messages"] = array("phrase_connect_rows", "phrase_disconnect_rows");
$page_vars["head_string"] =<<< END
  <link type="text/css" rel="stylesheet" href="$g_root_url/modules/custom_fields/global/css/styles.css">
  <script type="text/javascript" src="$g_root_url/global/scripts/sortable.js"></script>
  <script type="text/javascript" src="$g_root_url/modules/custom_fields/global/scripts/custom_fields.js"></script>
END;

$page_vars["head_js"] =<<< END
$(function() {
  $("#field_label").focus();
  $("#field_type").bind("change keyup", function() {
    var curr_val = $(this).val();
    if ($.inArray(curr_val, ["radios", "checkboxes", "select", "multi-select"]) != -1) {
      $("#field_options").show();
    } else {
      $("#field_options").hide();
    }
    if (curr_val == "radios" || curr_val == "checkboxes") {
      $(".orientation").show();
    } else {
      $(".orientation").hide();
    }
    if (curr_val == "option_list_or_form_field") {
      $("#field_type_default_value").hide();
    } else {
      $("#field_type_default_value").show();
    }
  });
  $("input[name=default_value_type]").bind("change", function() {
    if (this.value == "static") {
      $("#dv1").attr("disabled", "");
      $("#dv2").attr("disabled", "disabled");
    } else {
      $("#dv1").attr("disabled", "disabled");
      $("#dv2").attr("disabled", "");
    }
  });
  $init_options_js
});
END;

ft_display_module_page("templates/edit.tpl", $page_vars);
