  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}
  {ft_include file="messages.tpl"}

  <div class="subtitle underline margin_bottom_large">{$L.phrase_add_setting|upper}</div>

  <form action="{$same_page}" method="post">
    <input type="hidden" name="page" value="add_setting" />

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td valign="top" width="150">{$L.phrase_field_label}</td>
      <td>
        <input type="text" name="field_label" id="field_label" class="full_width" value="" />
        <div class="light_grey">{$L.phrase_setting_label_desc}</div>
      </td>
    </tr>
    <tr>
      <td valign="top">{$L.word_identifier}</td>
      <td>
        <input type="text" name="field_setting_identifier" value="" class="full_width" maxlength="50" />
        <div class="light_grey">{$L.text_field_setting_desc}</div>
      </td>
    </tr>
    <tr>
      <td>{$LANG.phrase_field_type}</td>
      <td>
        <select name="field_type" id="field_type">
          <option value="">{$LANG.phrase_please_select}</option>
          <optgroup label="{$L.phrase_static_field_types}">
            <option value="textbox">{$LANG.word_textbox}</option>
            <option value="textarea">{$LANG.word_textarea}</option>
            <option value="radios">{$LANG.phrase_radio_buttons}</option>
            <option value="checkboxes">{$LANG.word_checkboxes}</option>
            <option value="select">{$LANG.word_dropdown}</option>
            <option value="multi-select">{$LANG.phrase_multi_select}</option>
          </optgroup>
          <optgroup label="{$L.phrase_dynamic_field_types}">
            <option value="option_list_or_form_field">{$LANG.phrase_option_list} / {$LANG.phrase_form_field}</option>
          </optgroup>
        </select>
      </td>
    </tr>
    </table>

    <div id="field_type_default_value">
      <table cellspacing="1" cellpadding="1" border="0">
      <tr>
        <td width="150" valign="top">{$L.phrase_default_value}</td>
        <td>
          <table cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="25">
              <input type="radio" name="default_value_type" value="static" id="dvt1" checked />
            </td>
            <td width="120" valign="top"><label for="dvt1">{$L.phrase_static_value}</label></td>
            <td>
              <input type="text" name="default_value_static" id="dv1" class="med_width" />
            </td>
          </tr>
          <tr>
            <td valign="top">
              <input type="radio" name="default_value_type" value="dynamic" id="dvt2" />
            </td>
            <td valign="top"><label for="dvt2">{$L.phrase_dynamic_value}</label></td>
            <td>
              <input type="text" name="default_value_dynamic" id="dv2" class="med_width" disabled="disabled"  />
              <div class="light_grey">{$L.phrase_dynamic_value_format_desc}</div>
            </td>
          </tr>
          </table>
        </td>
      </tr>
      </table>
    </div>

    <div class="grey_box margin_top_large hidden" id="field_options">
      <div style="padding: 6px">

        <div class="orientation hidden">
          <table cellspacing="0" cellpadding="0" width="100%" class="margin_top">
          <tr>
            <td width="160"><label for="field_orientation">{$L.word_orientation}</label></td>
            <td>
              <input type="radio" name="field_orientation" id="fo1" value="horizontal" checked="checked" />
                <label for="fo1">{$LANG.word_horizontal}</label>
              <input type="radio" name="field_orientation" id="fo2" value="vertical" />
                <label for="fo2">{$LANG.word_vertical}</label>
            </td>
          </tr>
          </table>
        </div>

        <div id="custom_option_list">
          <div class="sortable groupable field_type_setting_options margin_bottom_large margin_top" id="{$sortable_id}">
            <ul class="header_row">
              <li class="col1">{$LANG.word_order}</li>
              <li class="col2">{$LANG.phrase_field_value}</li>
              <li class="col3">{$LANG.phrase_display_text}</li>
              <li class="col4 colN del"></li>
            </ul>
            <div class="clear"></div>
            <ul class="rows">
            </ul>
            <div class="clear"></div>
          </div>
          <div>
            <a href="#" onclick="return cf_ns.add_setting_option()">{$LANG.phrase_add_row}</a>
          </div>
        </div>

      </div>
    </div>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <p>
      <input type="submit" name="add" value="{$LANG.word_add}" />
    </p>
    {/if}

  </form>
