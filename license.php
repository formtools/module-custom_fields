<?php

require_once("../../global/library.php");
ft_init_module_page();
$request = array_merge($_POST, $_GET);

$module_id = ft_get_module_id_from_module_folder("custom_fields");

$page_vars = array();
$page_vars["module_info"] = ft_get_module($module_id);

ft_display_module_page("templates/license.tpl", $page_vars);
