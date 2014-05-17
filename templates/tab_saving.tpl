  <div class="subtitle underline margin_top_large">STORAGE SETTINGS</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}
  {include file="messages.tpl"}

  <form action="{$same_page}" method="post">

    <div class="hint margin_bottom_large">
      Form Tools automatically saves the value from all field types with what's stored in the name="{literal}{$NAME}{/literal}"
      field. But if you need to do something special - like combine the content from multiple fields - this is where you'd
      do it. The PHP entered here <b>must</b> define a $value variable containing the content to store.
    </div>

    <div class="editor">
      <textarea id="php_processing" name="php_processing" style="width: 100%; height: 280px">{$field_type_info.php_processing}</textarea>
    </div>
    <script type="text/javascript">
      var codemirror_field = new CodeMirror.fromTextArea("php_processing", {literal}{{/literal}
        parserfile: ["../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js"],
        path: "{$g_root_url}/global/codemirror/js/",
        stylesheet: "{$g_root_url}/global/codemirror/contrib/php/css/phpcolors.css"
      {literal}});{/literal}
    </script>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <div class="margin_top_large">
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
    {/if}

  </form>
