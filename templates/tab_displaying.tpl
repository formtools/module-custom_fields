  <div class="subtitle underline margin_top_large">{$L.phrase_display_settings|upper}</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}

  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post">

    <div class="inner_tabset" id="custom_fields_edit_field_displaying">
      <div class="tab_row fourCols">
        <div class="inner_tab1 {if $current_inner_tab == 1}selected{/if}">{$L.phrase_view_field}</div>
        <div class="inner_tab2 {if $current_inner_tab == 2}selected{/if}">{$L.phrase_edit_field}</div>
        <div class="inner_tab3 {if $current_inner_tab == 3}selected{/if}">CSS</div>
        <div class="inner_tab4 {if $current_inner_tab == 4}selected{/if}">Javascript</div>
      </div>
      <div class="inner_tab_content">
        <div class="inner_tab_content1 {if $current_inner_tab != 1 && $current_inner_tab != ""}hidden{/if}">

          <table width="100%">
          <tr>
            <td width="140" valign="top" rowspan="3">{$L.phrase_rendering_type}</td>
            <td width="80" class="italic green">{$L.word_fastest}</td>
            <td colspan="2">
              <input type="radio" name="rendering_type" id="rt1" value="none"
                {if $field_type_info.view_field_rendering_type == "none"}checked{/if} />
                <label for="rt1">{$L.phrase_output_db_value_directly}</label>
            </td>
          </tr>
          <tr>
            <td width="80" class="italic green" valign="top">{$L.word_fast}</td>
            <td width="160" valign="top">
              <input type="radio" name="rendering_type" id="rt2" value="php"
                {if $field_type_info.view_field_rendering_type == "php"}checked{/if} />
                <label for="rt2">{$L.phrase_php_function}</label>
            </td>
            <td>
              <select name="view_field_php_function_source">
                <option value="core" {if $field_type_info.view_field_php_function_source == "core"}selected{/if}>{$L.word_core}</option>
                <optgroup label="{$LANG.word_modules}">
                {foreach from=$modules item=module_info}
                  <option value="{$module_info.module_id}" {if $field_type_info.view_field_php_function_source == $module_info.module_id}selected{/if}>{$module_info.module_name}</option>
                {/foreach}
                </optgroup>
              </select><br />

              <input type="text" name="function_name" id="function_name" style="width:100%" value="{$field_type_info.view_field_php_function|escape}" />
            </td>
          </tr>
          <tr>
            <td width="80" class="italic orange">{$L.word_slower}</td>
            <td colspan="2">
              <input type="radio" name="rendering_type" id="rt3" value="smarty"
                {if $field_type_info.view_field_rendering_type == "smarty"}checked{/if} />
                <label for="rt3">{$L.phrase_smarty_content}</label>
            </td>
          </tr>
          </table>

          <div id="view_field_smarty_markup_section" {if $field_type_info.view_field_rendering_type != "smarty"}style="display: none"{/if}>
            <div class="hint margin_bottom_large">
              {$L.text_view_field_smarty_desc}
            </div>
            <div class="editor">
              <textarea id="view_field_smarty_markup" name="view_field_smarty_markup" style="height: 300px">{$field_type_info.view_field_smarty_markup|escape}</textarea>
            </div>
            <script type="text/javascript">
              {literal}
              var view_field_smarty_markup_field = new CodeMirror.fromTextArea(document.getElementById("view_field_smarty_markup"), {
                mode: {
                  name: "smarty",
                  baseMode: "text/html",
                  version: 3
                }
              });
              {/literal}
            </script>
            {assign var="context" value="view"}
            {include file="./available_variables.tpl"}
        </div>
        </div>
        <div class="inner_tab_content2 {if $current_inner_tab != 2}hidden{/if}">
          <div class="hint margin_bottom_large">
            {$L.text_edit_field_smarty_desc}
          </div>
          <div class="editor">
            <textarea id="edit_field_smarty_markup" name="edit_field_smarty_markup" style="height: 300px">{$field_type_info.edit_field_smarty_markup|escape}</textarea>
          </div>
          <script>
            var edit_field_markup_field = new CodeMirror.fromTextArea(document.getElementById("edit_field_smarty_markup"), {literal}{{/literal}
              mode: {
                name: "smarty",
                baseMode: "text/html",
                version: 3
              }
            {literal}});{/literal}
          </script>
          {assign var="context" value="edit"}
          {include file="./available_variables.tpl"}
        </div>
        <div class="inner_tab_content3 {if $current_inner_tab != 3}hidden{/if}">
          <div class="hint margin_bottom_large">{$L.text_css_desc}</div>
          <div class="editor">
            <textarea id="resources_css" name="resources_css" style="height: 400px">{$field_type_info.resources_css}</textarea>
          </div>
          <script>
            var include_css_field = new CodeMirror.fromTextArea(document.getElementById("resources_css"), {literal}{{/literal}
              mode: "css"
            {literal}});{/literal}
          </script>
        </div>
        <div class="inner_tab_content4 {if $current_inner_tab != 4}hidden{/if}">
          <div class="hint margin_bottom_large">{$L.text_js_desc}</div>
          <div class="editor">
            <textarea id="resources_js" name="resources_js" style="height: 400px">{$field_type_info.resources_js}</textarea>
          </div>
          <script type="text/javascript">
            var include_js_field = new CodeMirror.fromTextArea(document.getElementById("resources_js"), {literal}{{/literal}
              mode: "javascript"
            {literal}});{/literal}
          </script>
        </div>
      </div>
    </div>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <div class="margin_top_large">
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
    {/if}

  </form>
