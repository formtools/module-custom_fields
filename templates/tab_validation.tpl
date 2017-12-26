  <div class="subtitle underline margin_top_large">{$LANG.word_validation|upper}</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}

  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post">
    <input type="hidden" name="field_type_id" value="{$field_type_info.field_type_id}" />

    <div class="margin_bottom_large hint">
      {$L.text_validation_tab_intro}
    </div>

    {if $field_type_info.validation|@count == 0}
      <div class="notify margin_bottom">
        <div style="padding: 6px;">
          {$L.text_no_validation_rules_defined}
        </div>
      </div>
    {else}
      <div class="sortable validation_rules" id="{$sortable_id}">
        <input type="hidden" class="sortable__custom_delete_handler" value="cf_ns.delete_validation_rule" />
        <ul class="header_row">
          <li class="col1">{$LANG.word_order}</li>
          <li class="col2">{$LANG.phrase_validation_rule}</li>
          <li class="col3">{$L.word_label}</li>
          <li class="col4">{$L.phrase_default_error_message}</li>
          <li class="col5 edit"></li>
          <li class="col6 colN del"></li>
        </ul>
        <div class="clear"></div>
        <ul class="rows">
          {foreach from=$field_type_info.validation key=k item=i name=row}
            <li class="sortable_row">
              <div class="row_content">
                <div class="row_group{if $smarty.foreach.row.last} rowN{/if}">
                  <input type="hidden" class="sr_order" value="{$i.rule_id}" />
                  <ul>
                    <li class="col1 sort_col">{$i.list_order}</li>
                    <li class="col2">{display_rsv_rule_name rule=$i.rsv_rule L=$L}</li>
                    <li class="col3">{eval var=$i.rule_label}</li>
                    <li class="col4">{eval var=$i.default_error_message|escape}</li>
                    <li class="col5 edit"><a href="?page=edit_validation_rule&field_type_id={$field_type_id}&rule_id={$i.rule_id}"></a></li>
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
    {/if}

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <p>
      {if $field_type_info.validation|@count > 1}
        <input type="submit" name="update_order" value="{$LANG.word_update}" />
      {/if}
      <input type="button" value="{$L.phrase_add_validation_rule_rightarrow}" onclick="window.location='?page=add_validation_rule'" />
    </p>
    {/if}

  </form>
