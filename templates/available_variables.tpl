          <table cellspacing="1" cellpadding="0" width="100%" class="var_list_table">
          <tr>
            <th width="50%">{$L.phrase_core_variables}</th>
            <th width="50%">{$L.phrase_custom_field_setting_variables}</th>
          </tr>
          <tr>
            <td valign="top">
              <table>
              <tr>
                <td width="120" class="blue">$FORM_ID</td>
                <td>{$L.phrase_current_form_id}</td>
              </tr>
              <tr>
                <td class="blue">$SUBMISSION_ID</td>
                <td>{$L.phrase_current_submission_id}</td>
              </tr>
              <tr>
                <td class="blue">$FIELD_ID</td>
                <td>{$L.phrase_current_field_id}</td>
              </tr>
              <tr>
                <td class="blue">$NAME</td>
                <td>{$L.phrase_form_field_name}</td>
              </tr>
              <tr>
                <td class="blue">$COLNAME</td>
                <td>{$L.phrase_db_column_name}</td>
              </tr>
              <tr>
                <td class="blue">$VALUE</td>
                <td>{$L.phrase_value_stored_in_field}</td>
              </tr>
              {* if this is the View Field Markup tab, include the $CONTEXTPAGE var *}
              {if $context == "view"}
              <tr>
                <td class="blue">$CONTEXTPAGE</td>
                <td>{$L.phrase_context_desc}</td>
              </tr>
              {/if}
              <tr>
                <td class="blue">$ACCOUNT_INFO</td>
                <td>{$L.phrase_account_info_desc}</td>
              </tr>
              <tr>
                <td class="blue">$g_root_dir</td>
                <td>{$L.phrase_from_config_file}</td>
              </tr>
              <tr>
                <td class="blue">$g_root_url</td>
                <td>{$L.phrase_from_config_file}</td>
              </tr>
              </table>
            </td>
            <td valign="top">
              <table>
              {foreach from=$field_type_settings item=setting}
              <tr>
                <td class="blue">${$setting.field_setting_identifier}</td>
                <td></td>
              </tr>
              {/foreach}
              </table>
            </td>
          </tr>
          </tbody></table>
