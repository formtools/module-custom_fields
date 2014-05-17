<?php

function custom_fields__install($module_id)
{
  global $g_table_prefix, $LANG;

  $success = true;
  $message = "";

  $encrypted_key = isset($_POST["ek"]) ? $_POST["ek"] : "";
  $module_key    = isset($_POST["k"]) ? $_POST["k"] : "";

  if (empty($encrypted_key) || empty($module_key) || $encrypted_key != crypt($module_key, "gw"))
  {
    $success = false;
  }

  return array($success, $message);
}