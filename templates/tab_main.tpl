<div class="subtitle underline margin_top_large">{$LANG.phrase_main_settings|upper}</div>

{if $field_type_info.is_editable == "no" && !$g_cf_allow_editing_of_non_editable_fields}
    {assign var=g_success value=true}
    {assign var=g_message value=$L.text_non_editable_field_type}
{/if}
{ft_include file="messages.tpl"}

<form action="{$same_page}" method="post">
    <table cellspacing="1" cellpadding="0" class="list_table main_tab_table margin_bottom_large">
        <tr>
            <td class="pad_left_small" valign="top" width="200">{$L.phrase_field_type_name}</td>
            <td>
                <input type="text" name="field_type_name" value="{$field_type_info.field_type_name|escape}"
                       class="full_width"/>
                <div class="hint">{$L.text_field_type_name_desc}</div>
            </td>
        </tr>
        <tr>
            <td class="pad_left_small" valign="top">{$L.phrase_field_type_identifier}</td>
            <td class="medium_grey">{$field_type_info.field_type_identifier}</td>
        </tr>
        <tr>
            <td class="pad_left_small">{$L.phrase_field_type_group}</td>
            <td>
                <select name="group_id">
                    {foreach from=$field_type_groups item=group_info}
                        <option value="{$group_info.group_id}"
                                {if $field_type_info.group_id == $group_info.group_id}selected{/if}>{eval var=$group_info.group_name}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td class="pad_left_small">{$L.phrase_is_file_field}</td>
            <td>
                <input type="radio" name="is_file_field" value="yes" id="iff1"
                       {if $field_type_info.is_file_field == "yes"}checked{/if}
                /><label for="iff1">{$LANG.word_yes}</label>
                <input type="radio" name="is_file_field" value="no" id="iff2"
                       {if $field_type_info.is_file_field == "no"}checked{/if}
                /><label for="iff2">{$LANG.word_no}</label>
                <div class="hint">{$L.text_file_field_desc}</div>
            </td>
        </tr>
        <tr>
            <td class="pad_left_small">{$L.phrase_is_date_field}</td>
            <td>
                <input type="radio" name="is_date_field" value="yes" id="idf1"
                       {if $field_type_info.is_date_field == "yes"}checked{/if}
                /><label for="idf1">{$LANG.word_yes}</label>
                <input type="radio" name="is_date_field" value="no" id="idf2"
                       {if $field_type_info.is_date_field == "no"}checked{/if}
                /><label for="idf2">{$LANG.word_no}</label>
                <div class="hint">{$L.text_date_field_desc}</div>
            </td>
        </tr>
        <tr>
            <td class="pad_left_small">{$L.phrase_compatible_field_sizes}</td>
            <td>
                {field_sizes_dropdown name="compatible_field_sizes[]" multiple="true" size="5" default=$compatible_field_sizes}
                <div class="hint">{$L.text_field_size_desc}</div>
            </td>
        </tr>
        <tr>
            <td class="pad_left_small">{$L.phrase_raw_field_type_map}</td>
            <td>
                <select name="raw_field_type_map" id="raw_field_type_map">
                    <option value="">{$LANG.word_na}</option>
                    <optgroup label="{$LANG.phrase_field_types}">
                        {foreach from=$raw_field_types key=k item=i}
                            <option value="{$k}"
                                    {if $k == $field_type_info.raw_field_type_map}selected{/if}>{eval var="{$LANG.$i}"}</option>
                        {/foreach}
                    </optgroup>
                </select>

                <select name="raw_field_type_map_multi_select_id" id="raw_field_type_map_multi_select_id"
                        {if $field_type_info.raw_field_type_map != "radio-buttons" &&
                        $field_type_info.raw_field_type_map != "checkboxes" &&
                        $field_type_info.raw_field_type_map != "select" &&
                        $field_type_info.raw_field_type_map != "multi-select"}class="hidden"{/if}>
                    <option value="">{$L.phrase_select_setting_containing_option_list}</option>
                    {foreach from=$field_type_info.settings item=setting_info}
                        {if $setting_info.field_type == "option_list_or_form_field"}
                            <option value="{$setting_info.setting_id}"
                                    {if $field_type_info.raw_field_type_map_multi_select_id == $setting_info.setting_id}selected="selected"{/if}>
                                {eval var="{$setting_info.field_label}"}
                            </option>
                        {/if}
                    {/foreach}
                </select>
                <div class="hint">{$L.text_raw_field_type_desc}</div>
            </td>
        </tr>
    </table>

    <div>
        {if $show_reset_button}
            <input type="submit" name="reset_field_type"" value="{$L.phrase_reset_field_type}"/>
        {/if}

        {if $field_type_info.is_editable == "yes" || $g_cf_allow_editing_of_non_editable_fields}
            <input type="submit" name="update" value="{$LANG.word_update}"/>
        {/if}
    </div>
</form>
