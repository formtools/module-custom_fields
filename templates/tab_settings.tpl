  <div class="subtitle underline margin_top_large">CUSTOMIZABLE SETTINGS</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}
  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post">

    <div class="hint margin_bottom_large">
      {$L.text_settings_tab_desc}
    </div>

    {if $field_type_settings|@count == 0}
      <div class="notify">
        <div style="padding: 6px">{$L.notify_no_field_type_settings}</div>
      </div>
      <p>
        <input type="button" value="{$L.phrase_add_setting_rightarrow}" onclick="window.location='?page=add_setting'" />
      </p>
    {else}

      <form action="{$same_page}" method="post">
        <input type="hidden" name="field_type_id" value="{$field_type_info.field_type_id}" />
        <div class="sortable list_settings" id="{$sortable_id}">
          <ul class="header_row">
            <li class="col1">{$LANG.word_order}</li>
            <li class="col2">{$L.phrase_setting_name}</li>
            <li class="col3">{$LANG.phrase_field_type}</li>
            <li class="col4">{$L.word_identifier}</li>
            <li class="col5 edit"></li>
            <li class="col6 colN del"></li>
          </ul>
          <div class="clear"></div>
          <ul class="rows">
          {foreach from=$field_type_settings key=k item=i name=row}
            <li class="sortable_row">
              <div class="row_content">
                <div class="row_group{if $smarty.foreach.row.last} rowN{/if}">
                  <input type="hidden" class="sr_order" value="{$i.setting_id}" />
                  <ul>
                    <li class="col1 sort_col">{$i.list_order}</li>
                    <li class="col2">{eval var=$i.field_label}</li>
                    <li class="col3">
                      {if $i.field_type == "textbox"}
                        {$LANG.word_textbox}
                      {elseif $i.field_type == "textarea"}
                        {$LANG.word_textarea}
                      {elseif $i.field_type == "password"}
                        {$LANG.word_password}
                      {elseif $i.field_type == "radios"}
                        {$LANG.phrase_radio_buttons}
                      {elseif $i.field_type == "checkboxes"}
                        {$LANG.word_checkboxes}
                      {elseif $i.field_type == "select"}
                        {$LANG.word_dropdown}
                      {elseif $i.field_type == "multi-select"}
                        {$LANG.phrase_multi_select}
                      {elseif $i.field_type == "option_list_or_form_field"}
                        {$LANG.phrase_option_list} / {$LANG.phrase_form_field}
                      {/if}
                    </li>
                    <li class="col4">{$i.field_setting_identifier}</li>
                    <li class="col5 edit"><a href="edit.php?page=edit_setting&setting_id={$i.setting_id}"></a></li>
                    <li class="col6 colN del"></li>
                  </ul>
                  <div class="clear"></div>
                </div>
              </div>
              <div class="clear"></div>
            </li>
          {/foreach}
          </ul>
          <div class="clear"></div>
        </div>

        {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
        <p>
          <input type="submit" name="update" value="{$LANG.word_update}" />
          <input type="button" value="{$L.phrase_add_setting_rightarrow}" onclick="window.location='?page=add_setting'" />
        </p>
        {/if}

      </form>

    {/if}

  </form>
