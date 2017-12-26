<?php

use FormTools\Core;

/**
 * Displays the available list of RSV rules that field types may implement.
 *
 * @param array $params
 * @param object $smarty
 */
function smarty_function_rsv_rules($params, &$smarty)
{
    $LANG = Core::$L;

    $name_id = (isset($params["name_id"])) ? $params["name_id"] : "";
    $default = (isset($params["default"])) ? $params["default"] : "";
    $class = (isset($params["class"])) ? $params["class"] : "";
    $omit = (isset($params["omit"])) ? $params["omit"] : array();
    $L = $params["L"];

    $lines = array();
    $lines[] = "<select name=\"$name_id\" id=\"$name_id\" class=\"$class\">";
    $lines[] = "<option value=\"\">{$LANG["phrase_please_select"]}</option>";

    if (!in_array("required", $omit) || !in_array("valid_email", $omit) ||
        !in_array("digits_only", $omit) || !in_array("letters_only", $omit) || !in_array("is_alpha", $omit)) {
        $lines[] = "<optgroup label=\"{$L["phrase_standard_rsv_validation"]}\">";
        if (!in_array("required", $omit)) {
            $lines[] = "<option value=\"required\"" . (($default == "required") ? " selected" : "") . ">{$LANG["word_required"]}</option>";
        }
        if (!in_array("valid_email", $omit)) {
            $lines[] = "<option value=\"valid_email\"" . (($default == "valid_email") ? " selected" : "") . ">{$LANG["phrase_valid_email"]}</option>";
        }
        if (!in_array("digits_only", $omit)) {
            $lines[] = "<option value=\"digits_only\"" . (($default == "digits_only") ? " selected" : "") . ">{$LANG["phrase_numbers_only"]}</option>";
        }
        if (!in_array("letters_only", $omit)) {
            $lines[] = "<option value=\"letters_only\"" . (($default == "letters_only") ? " selected" : "") . ">{$LANG["phrase_letters_only"]}</option>";
        }
        if (!in_array("is_alpha", $omit)) {
            $lines[] = "<option value=\"is_alpha\"" . (($default == "is_alpha") ? " selected" : "") . ">{$LANG["phrase_alphanumeric"]}</option>";
        }

        $lines[] = "</optgroup>";
    }

    $lines[] = "<optgroup label=\"{$L["phrase_custom_validation"]}\">";
    $lines[] = "<option value=\"function\"" . (($default == "function") ? " selected" : "") . ">{$L["word_function"]}</option>";
    $lines[] = "</optgroup>";
    $lines[] = "</select>";

    echo implode("\n", $lines);
}
