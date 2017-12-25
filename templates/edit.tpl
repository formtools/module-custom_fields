{ft_include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0" class="margin_bottom_large">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_custom_fields.png" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {eval var=$field_type_info.field_type_name assign=field_type_name}
      {$field_type_name}
    </td>
  </tr>
  </table>

  {ft_include file='tabset_open.tpl'}
  {if     $page == "main"}
    {ft_include file='../../modules/custom_fields/templates/tab_main.tpl'}
  {elseif $page == "displaying"}
    {ft_include file='../../modules/custom_fields/templates/tab_displaying.tpl'}
  {elseif $page == "saving"}
    {ft_include file='../../modules/custom_fields/templates/tab_saving.tpl'}
  {elseif $page == "validation"}
    {ft_include file='../../modules/custom_fields/templates/tab_validation.tpl'}
  {elseif $page == "add_validation_rule"}
    {ft_include file='../../modules/custom_fields/templates/tab_add_validation_rule.tpl'}
  {elseif $page == "edit_validation_rule"}
    {ft_include file='../../modules/custom_fields/templates/tab_edit_validation_rule.tpl'}
  {elseif $page == "settings"}
    {ft_include file='../../modules/custom_fields/templates/tab_settings.tpl'}
  {elseif $page == "add_setting"}
    {ft_include file='../../modules/custom_fields/templates/tab_add_setting.tpl'}
  {elseif $page == "edit_setting"}
    {ft_include file='../../modules/custom_fields/templates/tab_edit_setting.tpl'}
  {else}
    {ft_include file='../../modules/custom_fields/templates/tab_main.tpl'}
  {/if}
  {ft_include file='tabset_close.tpl'}

{ft_include file='modules_footer.tpl'}
