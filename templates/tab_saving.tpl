  <div class="subtitle underline margin_top_large">STORAGE SETTINGS</div>

  {if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
  {/if}
  {ft_include file="messages.tpl"}

  <form action="{$same_page}" method="post">

    <div class="hint margin_bottom_large">
      {$L.text_saving_desc}
    </div>

    <div class="editor">
      <textarea id="php_processing" name="php_processing" style="width: 100%; height: 280px">{$field_type_info.php_processing}</textarea>
    </div>
    <script>
      var codemirror_field = new CodeMirror.fromTextArea(document.getElementById("php_processing"), {literal}{{/literal}
        mode: "php"
      {literal}});{/literal}
    </script>

    {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
    <div class="margin_top_large">
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
    {/if}

  </form>
