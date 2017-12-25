{ft_include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_custom_fields.png" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$L.phrase_shared_characteristics}
    </td>
  </tr>
  </table>

  {ft_include file="messages.tpl"}

  <div>
    {$L.text_shared_characteristics}
  </div>

  <form action="{$same_page}" method="post">
    <ul id="grouped_characteristics_table">
    {foreach from=$grouped_characteristics item=i}
      <li>
        <h2>
          {$i.group_name}
          <input type="hidden" name="all_group_names[]" class="group_name" value="{$i.group_name}" />
        </h2>
        <table class="list_table">
          <tr>
            <th>{$LANG.phrase_field_type}</th>
            <th>{$L.phrase_field_type_setting}</th>
            <th class="del"></th>
          </tr>
        {foreach from=$i.mapped item=m}
          <tr>
            <td>
              {display_field_types_dropdown name="`$i.group_name`[]" default=$m.field_type_identifier
                value_type="identifier" class="field_type_dropdown"}
            </td>
            <td class="settings_col">
              {display_field_type_settings_dropdown name="`$i.group_name`_settings[]"
                field_type_id=$m.field_type_id class="field_type_settings_dropdown"
                default=$m.field_type_setting_identifier}
            </td>
            <td class="del"></td>
          </tr>
        {/foreach}
        </table>
        <div class="margin_bottom_large">
          <a href="#" class="add_row_link">{$LANG.phrase_add_row}</a>
        </div>
      </li>
    {/foreach}
    </ul>

    <div class="clear"></div>
    <p>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </p>
  </form>

  <div class="hidden" id="dropdown_template">
    {display_field_types_dropdown name="template_NAME" class="field_type_dropdown" value_type="identifier"}
  </div>

{ft_include file='modules_footer.tpl'}
