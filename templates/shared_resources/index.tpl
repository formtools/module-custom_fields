{ft_include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="../images/icon_custom_fields.png" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="../">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$L.phrase_shared_resources}
    </td>
  </tr>
  </table>

  {ft_include file="messages.tpl"}

  <div class="margin_bottom_large">
    {$L.text_shared_resources_intro}
  </div>

  <form action="{$same_page}" method="post" id="shared_resources_form">
    <div class="inner_tabset" id="shared_resources">
	    <div class="tab_row threeCols">
	      <div class="inner_tab1 {if $current_inner_tab == 1 || $current_inner_tab == ""}selected{/if}">CSS</div>
	      <div class="inner_tab2 {if $current_inner_tab == 2}selected{/if}">JavaScript</div>
	      <div class="inner_tab3 {if $current_inner_tab == 3}selected{/if}">{$L.phrase_included_files}</div>
	    </div>
	    <div class="inner_tab_content">
	      <div class="inner_tab_content1 {if $current_inner_tab != 1 && $current_inner_tab != ""}hidden{/if}">
			    <div class="editor">
			      <textarea id="edit_submission_shared_resources_css" name="edit_submission_shared_resources_css"
			        style="height: 400px">{$edit_submission_shared_resources_css}</textarea>
			    </div>
			    <script type="text/javascript">
			      var edit_submission_shared_resources_css_field = new CodeMirror.fromTextArea(document.getElementById("edit_submission_shared_resources_css"), {literal}{{/literal}
			        mode: "css"
			      {literal}});{/literal}
			    </script>
			  </div>
	      <div class="inner_tab_content2 {if $current_inner_tab != 2}hidden{/if}">
			    <div class="editor">
			      <textarea id="edit_submission_shared_resources_js" name="edit_submission_shared_resources_js"
			        style="height: 400px">{$edit_submission_shared_resources_js}</textarea>
			    </div>
			    <script type="text/javascript">
			      var edit_submission_shared_resources_js_field = new CodeMirror.fromTextArea(document.getElementById("edit_submission_shared_resources_js"), {literal}{{/literal}
			        mode: "javascript"
			      {literal}});{/literal}
			    </script>
	      </div>
	      <div class="inner_tab_content3 {if $current_inner_tab != 3}hidden{/if}">
	        <div class="sortable shared_resources_included_files margin_bottom" id="shared_resources_included_files">
	          <ul class="header_row">
	            <li class="col1">{$LANG.word_order}</li>
	            <li class="col2">{$L.phrase_path_to_resource}</li>
	            <li class="col3 colN del"></li>
	          </ul>
	          <div class="clear"></div>
	          <ul class="rows">
	            {foreach from=$edit_submission_onload_resources item=i name=row}
	              {assign var=count value=$smarty.foreach.row.iteration}
	              <li class="sortable_row{if $smarty.foreach.row.last} rowN{/if}">
	                <div class="row_content">
	                  <div class="row_group{if $smarty.foreach.row.last} rowN{/if}">
	                    <input type="hidden" class="sr_order" value="{$count}" />
	                    <ul>
	                      <li class="col1 sort_col">{$count}</li>
	                      <li class="col2"><input type="text" name="resources[]" value="{$i|escape}" /></li>
	                      <li class="col3 colN del"></li>
	                    </ul>
	                    <div class="clear"></div>
	                  </div>
	                </div>
	                <div class="clear"></div>
	              </li>
	            {/foreach}
	            {if $edit_submission_onload_resources|@count == 0}
	              <li class="sortable_row">
	                <div class="row_content">
	                  <div class="row_group rowN">
	                    <input type="hidden" class="sr_order" value="1" />
	                    <ul>
	                      <li class="col1 sort_col">1</li>
	                      <li class="col2"><input type="text" name="resources[]" /></li>
	                      <li class="col3 colN del"></li>
	                    </ul>
	                    <div class="clear"></div>
	                  </div>
	                </div>
	                <div class="clear"></div>
	              </li>
	            {/if}
	          </ul>
	        </div>

	        <div>
	          <a href="#" onclick="return cf_ns.add_shared_resource()">{$LANG.phrase_add_row}</a>
	        </div>
	      </div>
	    </div>
	  </div>

    <div class="margin_top_large">
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>

  </form>

{ft_include file='modules_footer.tpl'}
