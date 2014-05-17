<?php

if (isset($request["update"]))
{
  list($g_success, $g_message) = cf_update_client_tab($field_type_id, $request);
}

$field_type_info     = ft_get_field_type($field_type_id);
$field_type_settings = ft_get_field_type_settings($field_type_id);
$modules = ft_get_modules();


$current_inner_tab = isset($_SESSION["ft"]["inner_tabs"]["custom_fields_edit_field_displaying"]) ?
  $_SESSION["ft"]["inner_tabs"]["custom_fields_edit_field_displaying"] : "";

$head_string .=<<< END
<script src="$g_root_url/global/codemirror/js/codemirror.js"></script>
END;

$page_vars["page"]              = $page;
$page_vars["head_string"]       = $head_string;
$page_vars["modules"]           = $modules;
$page_vars["current_inner_tab"] = $current_inner_tab;
$page_vars["field_type_info"]   = $field_type_info;
$page_vars["field_type_settings"] = $field_type_settings;
$page_vars["head_js"] =<<< END
$(function() {
  ft.init_inner_tabs();
  $("input[name=rendering_type]").bind("click", function() {
    if (this.value == "smarty") {
      $("#view_field_smarty_markup_section").show();
    } else {
      $("#view_field_smarty_markup_section").hide();
    }
  });
});
END;
ft_display_module_page("templates/edit.tpl", $page_vars);
