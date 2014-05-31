<?php

$MODULE = array();
$MODULE["author"]          = "Ben Keen";
$MODULE["author_email"]    = "ben.keen@gmail.com";
$MODULE["author_link"]     = "http://www.formtools.org";
$MODULE["version"]         = "1.0.7";
$MODULE["date"]            = "2014-05-30";
$MODULE["is_premium"]      = "no";
$MODULE["origin_language"] = "en_us";


$MODULE["nav"] = array(
	"module_name"             => array('{$module_dir}/index.php', false),
	"phrase_shared_resources" => array('{$module_dir}/shared_resources/', false),
	"phrase_shared_characteristics" => array('{$module_dir}/shared_characteristics.php', false),
	"word_license" => array('{$module_dir}/license.php', false)
);
