  <div class="subtitle underline margin_top_large">{$L.phrase_edit_validation_rule|upper}</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}

  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="page" value="edit_validation_rule" />
    <input type="hidden" name="rule_id" value="{$rule.rule_id}" />
    <input type="hidden" name="field_type_id" value="{$field_type_info.field_type_id}" />

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td width="150">{$LANG.phrase_validation_rule}</td>
      <td>{rsv_rules name_id="rsv_rule" default=$rule.rsv_rule omit=$existing_validation_rules L=$L}</td>
    </tr>
    <tr>
      <td valign="top">{$L.word_label}</td>
      <td>
        <input type="text" name="rule_label" value="{$rule.rule_label|escape}" class="full_width" maxlength="100" />
        <div class="light_grey">{$L.text_rule_label_desc}</div>
      </td>
    </tr>
    <tr>
      <td valign="top">{$L.phrase_default_error_message}</td>
      <td>
        <input type="text" name="default_error_message" value="{$rule.default_error_message|escape}" class="full_width" />
        <div class="light_grey">{$L.text_default_error_message_desc}</div>
      </td>
    </tr>
    </table>

    <div id="custom_function_settings" {if $rule.rsv_rule != "function"}class="hidden"{/if}>
      <table cellspacing="1" cellpadding="1" border="0">
      <tr>
        <td width="150" valign="top">{$L.phrase_custom_function}</td>
        <td><input type="text" name="custom_function" value="{$rule.custom_function|escape}" maxlength="100" class="full_width" /></td>
      </tr>
      <tr>
        <td valign="top">{$L.phrase_is_required_field_q}</td>
        <td>
          <input type="radio" name="custom_function_required" id="cfe1" value="yes" {if $rule.custom_function_required == "yes"}checked="checked"{/if} />
            <label for="cfe1">{$LANG.word_yes}</label>
          <input type="radio" name="custom_function_required" id="cfe2" value="no" {if $rule.custom_function_required != "yes"}checked="checked"{/if} />
            <label for="cfe2">{$LANG.word_no}</label>
          <div class="light_grey">{$L.text_required_custom_function_desc}</div>
        </td>
      </tr>
      </table>
    </div>

    <div id="standard_settings" {if $rule.rsv_rule == "function"}class="hidden"{/if}>
      <table cellspacing="1" cellpadding="1" border="0">
      <tr>
        <td width="150" valign="top">{$L.phrase_field_name}</td>
        <td>
          <input type="text" name="rsv_field_name" value="{$rule.rsv_field_name|escape}" maxlength="255" class="full_width" />
          <div class="light_grey">{$L.text_rsv_field_name_desc}</div>
        </td>
      </tr>
      </table>
    </div>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <p>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </p>
    {/if}

  </form>
