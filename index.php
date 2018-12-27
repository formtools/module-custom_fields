<?php

require_once("../../global/library.php");

use FormTools\Core;
use FormTools\FieldTypes as CoreFieldTypes;
use FormTools\Modules;
use FormTools\Modules\CustomFields\FieldTypes;

$module = Modules::initModulePage("admin");
$root_url = Core::getRootUrl();
$LANG = Core::$L;
$L = $module->getLangStrings();

$sortable_id = "custom_fields";

$success = true;
$message = "";
if (isset($request["update_page"])) {
	$request["sortable_id"] = $sortable_id;
	list($success, $message) = FieldTypes::updateCustomFields($request, $L);

	// if the user just disabled some field types, reset all usages of them to textboxes
	if (!empty($request["disabled_field_types"])) {
		$field_type_ids = explode(",", $request["disabled_field_types"]);
		foreach ($field_type_ids as $id) {
			FieldTypes::resetFieldTypeUsagesToTextboxes($id);
		}
	}

	// if the user just deleted a custom field, override the default update message
	if (!empty($request["delete_field_type"])) {
		list($success, $message) = FieldTypes::deleteFieldType($request["delete_field_type"], $L);
	}
}

$grouped_field_types = CoreFieldTypes::getGroupedFieldTypes(false);
$id_to_identifier = CoreFieldTypes::getFieldTypeIdToIdentifierMap();

$enabled_field_type_ids = array();
foreach ($grouped_field_types as $group) {
	foreach ($group["field_types"] as $field_type) {
		if ($field_type["is_enabled"] === "yes") {
			$enabled_field_type_ids[] = $field_type["field_type_id"];
		}
	}
}
$enabled_field_type_ids_str = implode(", ", $enabled_field_type_ids);

$identifiers = array();
foreach (array_values($id_to_identifier) as $identifier) {
	$identifiers[] = "'$identifier'";
}
$existing_field_type_identifiers_js = "existing_field_type_identifiers = [" . implode(",", $identifiers) . "];";

$page_vars = array(
	"g_success" => $success,
	"g_message" => $message,
	"grouped_field_types" => $grouped_field_types,
	"sortable_id" => $sortable_id,
	"field_type_groups" => CoreFieldTypes::getFieldTypeGroups(),
	"js_messages" => array(
		"word_cancel",
		"word_edit",
		"phrase_create_group",
		"word_yes",
		"word_no",
		"phrase_please_confirm",
		"confirm_delete_group",
		"word_error",
		"word_okay",
		"phrase_delete_field_type"
	),
	"module_js_messages" => array(
		"phrase_delete_field_type",
		"confirm_delete_field_type",
		"confirm_delete_field_type_in_use",
		"notify_cannot_delete_nonempty_group"
	)
);

$page_vars["head_js"] = <<< END
$(function() {
  $existing_field_type_identifiers_js

  $(".add_field_link").live("click", function(e) {
    e.preventDefault();
    var group_id = $(this).closest(".sortable_group").find(".group_order").val();
    $("#add_field_popup .group_id option[value=" + group_id + "]").each(function() {
      this.setAttribute("selected", "selected");
    });
    $("#add_field_popup").dialog("open");
  });

  $(".group_name").live("blur", cf_ns.update_add_field_group_dropdown);

  // create our dialog and embed it, hidden in the page
  ft.create_dialog({
    dialog:    $("#add_field_popup"),
    auto_open: false,
    title:     "{$L["phrase_add_field_type"]}",
    min_width: 600,
    buttons: [{
      text:  $("<div />").html("{$L["phrase_add_field_type_rightarrow"]}").text(),
      click: function(e) {
        var field_type_name        = $.trim($(this).find(".field_type_name").val());
        var field_type_identifier  = $.trim($(this).find(".field_type_identifier").val());
        var group_id               = $(this).find(".group_id").val();
        var original_field_type_id = $(this).find(".original_field_type_id").val();

        if (field_type_name == "") {
          var error_box = $(this).find(".add_field_error");
          error_box.html("<div style=\"padding: 6px\">{$L["validation_no_field_type_name"]}</div>");
          error_box.show();
        } else if (field_type_identifier == '') {
          var error_box = $(this).find(".add_field_error");
          error_box.html("<div style=\"padding: 6px\">{$L["validation_no_field_type_identifier"]}</div>");
          error_box.show();
        } else if ($.inArray(field_type_identifier, existing_field_type_identifiers) != -1) {
          var error_box = $(this).find(".add_field_error");
          error_box.html("<div style=\"padding: 6px\">{$L["validation_field_type_identifier_taken"]}</div>");
          error_box.show();
        } else {
          ft.dialog_activity_icon(this, "show");
          ft.dialog_disable_button(this, $("<div />").html("{$L["phrase_add_field_type_rightarrow"]}").text());
          $.ajax({
            url:  g.root_url + "/modules/custom_fields/code/actions.php",
            data: {
              action:                 "add_field",
              field_type_name:        field_type_name,
              field_type_identifier:  field_type_identifier,
              group_id:               group_id,
              original_field_type_id: original_field_type_id
            },
            dataType: "html",
            type:     "POST",

            // when we've gotten the new data type, we store the ID in the form and submit the whole shebang.
            // This ensures that whatever changes the user just made (e.g. adding a new group) will be saved
            // as they'd expect
            success: function(data) {
              var new_field_id = parseInt(data);
              $("#new_field_type_id").val(new_field_id);
              window.location = "edit.php?page=main&field_type_id=" + new_field_id;
            },
            error: ft.error_handler
          });
        }
      }
    },
    {
      text:  "{$LANG["word_cancel"]}",
      click: function() { $(this).dialog("close"); }
    }]
  });

  var field_type_info_dialog = $("<div></div>");

  $("li.info").bind("click", function() {
    var field_type_id = $(this).closest(".row_group").find(".sr_order").val();

    ft.create_dialog({
      dialog:     field_type_info_dialog,
      title:      "{$L["phrase_field_type_information"]}",
      min_width:  400,
      content: "<div id=\"field_type_info\"><div class=\"ajax_activity\"></div></div>",
      buttons: [{
        text: "{$LANG["word_close"]}",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });

    $.ajax({
      url:      g.root_url + "/modules/custom_fields/code/actions.php",
      data:     { action: "get_undeletable_field_type_info", field_type_id: field_type_id },
      type:     "POST",
      dataType: "html",
      success: function(data) {
        $("#field_type_info").html(data);
      }
    });
  });
  
  // if the user disabled any field types, alert them to notify any usages of those fields will be set to textfields. 
  // This prevents anything getting out of whack with the system
  var allow_submit_override = false;
  $("#custom_fields_form").bind("submit", function () {
  	var originally_enabled_field_type_ids = [$enabled_field_type_ids_str];
  	
  	var selected = [];
  	$("input[name='enabled_field_types[]']:checked").each(function (index, item) {
  		selected.push(parseInt(item.value, 10));
	});
  	
	var newly_disabled_items = [];
	for (var i=0; i<originally_enabled_field_type_ids.length; i++) {
		if (selected.indexOf(originally_enabled_field_type_ids[i]) === -1) {
			newly_disabled_items.push(originally_enabled_field_type_ids[i]);
		}
	}
	
	if (newly_disabled_items.length > 0 && !allow_submit_override) {
		ft.create_dialog({
			dialog:     field_type_info_dialog,
			title:      "{$L["phrase_warning_disabling_fields"]}",
			popup_type: "warning",
			min_width:  400,
			content: "{$L["text_disabling_field_type_warning"]}",
			buttons: [{
				text: "{$LANG["word_continue"]}",
				click: function() {
					$("#disabled_field_types").val(newly_disabled_items.join(','));
					allow_submit_override = true;
					$("#custom_fields_form").submit();
				}
			}, {
				text: "{$LANG["word_cancel"]}",
				click: function() {
					$(this).dialog("close");
				}
			}]
		});
		return false;
	} else {
		return true;
	}
  });
  
});
END;

$module->displayPage("templates/index.tpl", $page_vars);
