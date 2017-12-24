<?php

/**
 * Displays the available list of RSV rules that field types may implement.
 *
 * @param array $params
 * @param object $smarty
 */
function smarty_function_display_rsv_rule_name($params, &$smarty)
{
  global $g_table_prefix, $LANG;

  $rule = (isset($params["rule"])) ? $params["rule"] : "";

  switch ($rule)
  {
  	case "required":
  		echo $LANG["word_required"];
  		break;
    case "valid_email":
    	echo $LANG["phrase_valid_email"];
      break;
    case "digits_only":
    	echo $LANG["phrase_numbers_only"];
      break;
    case "letters_only":
    	echo $LANG["phrase_letters_only"];
      break;
    case "is_alpha":
    	echo $LANG["phrase_alphanumeric"];
      break;
    case "function":
    	echo $LANG["custom_fields"]["phrase_custom_function"];
      break;
    case "function_required":
    	echo $LANG["custom_fields"]["phrase_custom_function_required"];
      break;
  }
}