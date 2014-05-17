<?php

if (isset($request["update"]))
{
	list($g_success, $g_message) = cf_update_server_tab($field_type_id, $request);
}
$field_type_info = ft_get_field_type($field_type_id);

$head_string .=<<< END
<script src="$g_root_url/global/codemirror/js/codemirror.js"></script>
END;


$page_vars["page"]            = $page;
$page_vars["head_string"]     = $head_string;
$page_vars["field_type_info"] = $field_type_info;

ft_display_module_page("templates/edit.tpl", $page_vars);
