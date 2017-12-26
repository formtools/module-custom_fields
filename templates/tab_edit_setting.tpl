  <div class="previous_page_icon">
    <a href="edit.php?page=settings"><img src="{$images_url}/up.jpg" title="{$LANG.phrase_previous_page}" alt="{$LANG.phrase_previous_page}" border="0" /></a>
  </div>

  <div class="subtitle underline margin_top_large">{$L.phrase_edit_setting|upper}</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}
  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post">
    <input type="hidden" name="page" value="edit_setting" />

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td width="150">{$L.phrase_field_label}</td>
      <td><input type="text" name="field_label" id="field_label" class="full_width" value="{$field_type_setting.field_label|escape}" /></td>
    </tr>
    <tr>
      <td>{$L.word_identifier}</td>
      <td class="light_grey">{$field_type_setting.field_setting_identifier}</td>
    </tr>
    <tr>
      <td>{$LANG.phrase_field_type}</td>
      <td>
        <select name="field_type" id="field_type">
          <option value="">{$LANG.phrase_please_select}</option>
          <optgroup label="{$L.phrase_static_field_types}">
            <option value="textbox"      {if $field_type_setting.field_type == "textbox"}selected{/if}>{$LANG.word_textbox}</option>
            <option value="textarea"     {if $field_type_setting.field_type == "textarea"}selected{/if}>{$LANG.word_textarea}</option>
            <option value="radios"       {if $field_type_setting.field_type == "radios"}selected{/if}>{$LANG.phrase_radio_buttons}</option>
            <option value="checkboxes"   {if $field_type_setting.field_type == "checkboxes"}selected{/if}>{$LANG.word_checkboxes}</option>
            <option value="select"       {if $field_type_setting.field_type == "select"}selected{/if}>{$LANG.word_dropdown}</option>
            <option value="multi-select" {if $field_type_setting.field_type == "multi-select"}selected{/if}>{$LANG.phrase_multi_select}</option>
          </optgroup>
          <optgroup label="{$L.phrase_dynamic_field_types}">
            <option value="option_list_or_form_field" {if $field_type_setting.field_type == "option_list_or_form_field"}selected{/if}>{$LANG.phrase_option_list} / {$LANG.phrase_form_field}</option>
          </optgroup>
        </select>
      </td>
    </tr>
    </table>

    <div id="field_type_default_value" {if $field_type_setting.field_type == "option_list_or_form_field"}class="hidden"{/if}>
      <table cellspacing="1" cellpadding="1" border="0">
      <tr>
        <td width="150" valign="top">{$L.phrase_default_value}</td>
        <td>

          <table cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="25">
              <input type="radio" name="default_value_type" value="static" id="dvt1"
                {if $field_type_setting.default_value_type == "static"}checked{/if} />
            </td>
            <td width="120" valign="top"><label for="dvt1">Static value</label></td>
            <td>
              <input type="text" name="default_value_static" id="dv1" class="med_width"
                {if $field_type_setting.default_value_type == "static"}
                  value="{$field_type_setting.default_value|escape}"
                {else}
                  disabled
                {/if}
              />
            </td>
          </tr>
          <tr>
            <td valign="top">
              <input type="radio" name="default_value_type" value="dynamic" id="dvt2"
                {if $field_type_setting.default_value_type == "dynamic"}checked{/if} />
            </td>
            <td valign="top"><label for="dvt2">Dynamic value</label></td>
            <td>
              <input type="text" name="default_value_dynamic" id="dv2" class="med_width"
                {if $field_type_setting.default_value_type == "dynamic"}
                  value="{$field_type_setting.default_value|escape}"
                {else}
                  disabled
                {/if}
              />
              <div class="light_grey">Format: setting_name,module_folder/"core"</div>
            </td>
          </tr>
          </table>
        </td>
      </tr>
      </table>
    </div>

    <div id="field_options" class="grey_box margin_top_large {if $field_type_setting.field_type != 'select' && $field_type_setting.field_type != 'radios' && $field_type_setting.field_type != 'checkboxes' && $field_type_setting.field_type != 'multi-select'}hidden{/if}">
      <div style="padding: 6px">

        <div class="orientation {if $field_type_setting.field_type != 'radios' && $field_type_setting.field_type != 'checkboxes'}hidden{/if}">
          <table cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td width="160">{$L.word_orientation}</td>
            <td>
              <input type="radio" name="field_orientation" id="fo1" value="horizontal" {if $field_type_setting.field_orientation == "horizontal" || $field_type_setting.field_orientation == "na"}checked{/if} />
                <label for="fo1">{$LANG.word_horizontal}</label>
              <input type="radio" name="field_orientation" id="fo2" value="vertical" {if $field_type_setting.field_orientation == "vertical"}checked{/if} />
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
            {assign var=previous_item value=""}
            {foreach from=$field_type_setting.options item=option name=row}
              {assign var=count value=$smarty.foreach.row.iteration}

                {if $option.is_new_sort_group == "yes"}
                  {if $previous_item != ""}
                    </div>
                    <div class="clear"></div>
                  </li>
                  {/if}
                 <li class="sortable_row">
                   {if $smarty.foreach.row.last}
                     {assign var=next_item_is_new_sort_group value="yes"}
                   {else}
                       {assign var=next_item_is_new_sort_group value=$field_type_setting.options[$smarty.foreach.row.iteration].is_new_sort_group}
                   {/if}
                   <div class="row_content{if $next_item_is_new_sort_group == 'no'} grouped_row{/if}">
                {/if}

                {assign var=previous_item value=$option}

                <div class="row_group{if $smarty.foreach.row.last} rowN{/if}">
                  <input type="hidden" class="sr_order" value="{$count}" />
                  <ul>
                    <li class="col1 sort_col">{$count}</li>
                    <li class="col2"><input type="text" name="option_value_{$count}" value="{$option.option_value|escape}" /></li>
                    <li class="col3"><input type="text" name="option_text_{$count}" value="{$option.option_text|escape}" /></li>
                    <li class="col4 colN del"></li>
                  </ul>
                  <div class="clear"></div>
                </div>

              {if $smarty.foreach.row.last}
                </div>
                <div class="clear"></div>
              </li>
              {/if}

            {/foreach}
            </ul>
            <div class="clear"></div>
          </div>

          <div>
            <a href="#" onclick="return cf_ns.add_setting_option()">{$LANG.phrase_add_row}</a>
          </div>

        </div>
      </div>

      <script>
      cf_ns.num_rows = {$field_type_setting.options|@count};
      </script>

    </div>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <p>
      <input type="submit" name="update" value="{$LANG.word_update|upper}" />
    </p>
    {/if}

  </form>
