  <div class="subtitle underline margin_top_large">{$L.phrase_display_settings|upper}</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}

  {include file="messages.tpl"}

  <form action="{$same_page}" method="post">

    <div class="inner_tabset" id="custom_fields_edit_field_displaying">
      <div class="tab_row fourCols">
        <div class="inner_tab1 {if $current_inner_tab == 1 || $current_inner_tab == ""}selected{/if}">View Field</div>
        <div class="inner_tab2 {if $current_inner_tab == 2}selected{/if}">Edit Field</div>
        <div class="inner_tab3 {if $current_inner_tab == 3}selected{/if}">CSS</div>
        <div class="inner_tab4 {if $current_inner_tab == 4}selected{/if}">Javascript</div>
      </div>
      <div class="inner_tab_content">
        <div class="inner_tab_content1 {if $current_inner_tab != 1 && $current_inner_tab != ""}hidden{/if}">

          <table width="100%">
          <tr>
            <td width="140" valign="top" rowspan="3">Rendering type</td>
            <td width="80" class="italic green">Fastest</td>
            <td colspan="2">
              <input type="radio" name="rendering_type" id="rt1" value="none"
                {if $field_type_info.view_field_rendering_type == "none"}checked{/if} />
                <label for="rt1">None, output content of database field directly</label>
            </td>
          </tr>
          <tr>
            <td width="80" class="italic green" valign="top">Fast</td>
            <td width="160" valign="top">
              <input type="radio" name="rendering_type" id="rt2" value="php"
                {if $field_type_info.view_field_rendering_type == "php"}checked{/if} />
                <label for="rt2">PHP Function</label>
            </td>
            <td>
              <select name="view_field_php_function_source">
                <option value="core" {if $field_type_info.view_field_php_function_source == "core"}selected{/if}>{$LANG.word_core}</option>
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
            <td width="80" class="italic orange">Slower</td>
            <td colspan="2">
              <input type="radio" name="rendering_type" id="rt3" value="smarty"
                {if $field_type_info.view_field_rendering_type == "smarty"}checked{/if} />
                <label for="rt3">Smarty content</label>
            </td>
          </tr>
          </table>

          <div id="view_field_smarty_markup_section" {if $field_type_info.view_field_rendering_type != "smarty"}style="display: none"{/if}>
            <div class="hint margin_bottom_large">
              This field contains the Smarty used to generate your field markup - it's used anywhere the field
              is displayed. If left blank, the unmodified content of the database field will be outputted.
            </div>
            <div class="editor">
              <textarea id="view_field_smarty_markup" name="view_field_smarty_markup" style="height: 300px">{$field_type_info.view_field_smarty_markup|escape}</textarea>
            </div>
            <script type="text/javascript">
              var view_field_smarty_markup_field = new CodeMirror.fromTextArea("view_field_smarty_markup", {literal}{{/literal}
                parserfile: ["parsexml.js"],
                path: "{$g_root_url}/global/codemirror/js/",
                stylesheet: "{$g_root_url}/global/codemirror/css/xmlcolors.css"
              {literal}});{/literal}
            </script>
            {assign var="context" value="view"}
            {include file="../../modules/custom_fields/templates/available_variables.tpl"}
        </div>
        </div>
        <div class="inner_tab_content2 {if $current_inner_tab != 2}hidden{/if}">
          <div class="hint margin_bottom_large">
            This field contains the Smarty used to generate the field for when it's being edited.
          </div>
          <div class="editor">
            <textarea id="edit_field_smarty_markup" name="edit_field_smarty_markup" style="height: 300px">{$field_type_info.edit_field_smarty_markup|escape}</textarea>
          </div>
          <script type="text/javascript">
            var edit_field_markup_field = new CodeMirror.fromTextArea("edit_field_smarty_markup", {literal}{{/literal}
              parserfile: ["parsexml.js"],
              path: "{$g_root_url}/global/codemirror/js/",
              stylesheet: "{$g_root_url}/global/codemirror/css/xmlcolors.css"
            {literal}});{/literal}
          </script>
          {assign var="context" value="edit"}
          {include file="../../modules/custom_fields/templates/available_variables.tpl"}
        </div>
        <div class="inner_tab_content3 {if $current_inner_tab != 3}hidden{/if}">
          <div class="hint margin_bottom_large">
            The CSS specified here is included <i>once</i> for all of your field types used in the page.
          </div>
          <div class="editor">
            <textarea id="resources_css" name="resources_css" style="height: 300px">{$field_type_info.resources_css}</textarea>
          </div>
          <script type="text/javascript">
            var include_css_field = new CodeMirror.fromTextArea("resources_css", {literal}{{/literal}
              parserfile: ["parsecss.js"],
              path: "{$g_root_url}/global/codemirror/js/",
              stylesheet: "{$g_root_url}/global/codemirror/css/csscolors.css"
            {literal}});{/literal}
          </script>
        </div>
        <div class="inner_tab_content4 {if $current_inner_tab != 4}hidden{/if}">
          <div class="hint margin_bottom_large">
            The Javascript specified here is included <i>once</i> for all of your field types used in the page.
          </div>
          <div class="editor">
            <textarea id="resources_js" name="resources_js" style="height: 300px">{$field_type_info.resources_js}</textarea>
          </div>
          <script type="text/javascript">
            var include_js_field = new CodeMirror.fromTextArea("resources_js", {literal}{{/literal}
              parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
              path: "{$g_root_url}/global/codemirror/js/",
              stylesheet: "{$g_root_url}/global/codemirror/css/jscolors.css"
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
