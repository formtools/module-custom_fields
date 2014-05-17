          <table cellspacing="1" cellpadding="0" width="100%" class="var_list_table">
          <tr>
            <th width="50%">Core Variables</th>
            <th width="50%">Custom Field Setting Variables</th>
          </tr>
          <tr>
            <td valign="top">
              <table>
              <tr>
                <td width="120" class="blue">$FORM_ID</td>
                <td>The current form ID</td>
              </tr>
              <tr>
                <td class="blue">$SUBMISSION_ID</td>
                <td>The current submission ID</td>
              </tr>
              <tr>
                <td class="blue">$FIELD_ID</td>
                <td>The current field ID</td>
              </tr>
              <tr>
                <td class="blue">$NAME</td>
                <td>The form field name</td>
              </tr>
              <tr>
                <td class="blue">$COLNAME</td>
                <td>The database column name</td>
              </tr>
              <tr>
                <td class="blue">$VALUE</td>
                <td>The value stored in this field</td>
              </tr>
              {* if this is the View Field Markup tab, include the $CONTEXTPAGE var *}
              {if $context == "view"}
              <tr>
                <td class="blue">$CONTEXTPAGE</td>
                <td>The page where your field is being outputted: "edit_submission" or "submission_listing"</td>
              </tr>
              {/if}
              <tr>
                <td class="blue">$ACCOUNT_INFO</td>
                <td>Information about the current logged in user</td>
              </tr>
              <tr>
                <td class="blue">$g_root_dir</td>
                <td>The value from your config.php file</td>
              </tr>
              <tr>
                <td class="blue">$g_root_url</td>
                <td>The value from your config.php file</td>
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
