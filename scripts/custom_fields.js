var cf_ns = {};
cf_ns.num_rows = 0;
cf_ns.__current_field_type_id = null;
cf_ns.num_validation_rules = null; // initialized on page load (Validation tab)

cf_ns.add_setting_option = function() {
  var currRow = ++cf_ns.num_rows;

  var li0 = $("<li class=\"col0\"></li>");
  var li1 = $("<li class=\"col1 sort_col\">" + currRow + "</li>");
  var li2 = $("<li class=\"col2\"><input type=\"text\" name=\"option_value_" + currRow + "\" id=\"option_value_" + currRow + "\" /></li>");
  var li3 = $("<li class=\"col3\"><input type=\"text\" name=\"option_text_" + currRow + "\" id=\"option_text_" + currRow + "\" /></li>");
  var li4 = $("<li class=\"col4 colN del\"></li>");

  var ul = $("<ul></ul>");
  ul.append(li0);
  ul.append(li1);
  ul.append(li2);
  ul.append(li3);
  ul.append(li4);

  var main_div = $("<div class=\"row_group\"><input type=\"hidden\" class=\"sr_order\" value=\"" + currRow + "\" /></div>");
  main_div.append(ul);
  main_div.append("<div class=\"clear\"></div>");

  var html = sortable_ns.get_sortable_row_markup({ row_group: main_div });
  $(".rows").append(sortable_ns.get_sortable_row_markup({ row_group: main_div }));
  sortable_ns.reorder_rows($(".field_type_setting_options"));

  return false;
}


cf_ns.create_new_group = function() {
  ft.dialog_activity_icon($("#add_group_popup"), "show");
  $.ajax({
    url:      g.root_url + "/modules/custom_fields/code/actions.php",
    type:     "POST",
    dataType: "json",
    data:     { group_name: $("#add_group_popup").find(".new_group_name").val(), action: "create_new_group" },
    success:  cf_ns.create_new_group_response,
    error:    ft.error_handler
  });
  return false;
}


cf_ns.create_new_group_response = function(data) {
  ft.dialog_activity_icon($("#add_group_popup"), "hide");
  $("#add_group_popup").dialog("close");
  sortable_ns.insert_new_group({
    group_id:   data.group_id,
    group_name: data.group_name
  });

  // manually update the dropdown Group list in the Add Field popup
  cf_ns.update_add_field_group_dropdown();
}


cf_ns.delete_group = function(el) {
  if ($(el).closest(".sortable_group").find(".rows").hasClass("has_rows_onload")) {
    ft.create_dialog({
      dialog:     sortable_ns.delete_nonempty_group_dialog,
      title:      g.messages["word_error"],
      content:    g.messages["notify_cannot_delete_nonempty_group"],
      popup_type: "error",
      buttons: [{
        "text":  g.messages["word_okay"],
        "click": function() {
          $(this).dialog("close");
        }
      }]
    });
  } else {
    ft.dialog_activity_icon(sortable_ns.delete_group_dialog, "show");
    var group_id = $(el).closest(".sortable_group_header").find(".group_order").val();
    var form = $(el).closest("form");
    form.append("<input type=\"hidden\" name=\"custom_fields_sortable__delete_group\" class=\"sortable__delete_group\" value=\"" + group_id + "\" />");
    form.trigger("submit");
  }
}


cf_ns.update_add_field_group_dropdown = function() {
  var updated_options = [];
  $(".sortable_group").each(function() {
    updated_options.push({
      value: $(this).find(".group_order").val(),
      text:  $(this).find(".group_name").val()
    });
  });

  var option_str = "";
  for (var i=0; i<updated_options.length; i++) {
    option_str += "<option value=\"" + updated_options[i].value + "\">" + updated_options[i].text + "</option>\n";
  }

  $("#original_field_group_id").html(option_str);
}


cf_ns.delete_row = function(el) {
  var field_type_id = $(el).closest(".row_group").find(".sr_order").val();
  cf_ns.__current_field_type_id = field_type_id;

  ft.create_dialog({
    dialog:  $("#delete_group_popup"),
    title:   g.messages["phrase_delete_field_type"],
    buttons: [{
      text:  g.messages["word_cancel"],
      click: function() {
        $(this).dialog("close");
      }
    }],

    // when the dialog is opened, check the database to see if this field type is being used.
    open: function() {
      ft.dialog_activity_icon(this, "show");
      $.ajax({
        url:      g.root_url + "/modules/custom_fields/code/actions.php",
        data:     { action: "get_field_type_usage", field_type_id: field_type_id },
        dataType: "json",
        success:  cf_ns.delete_custom_field_response,
        error:    ft.error_handler
      });
    }
  });
}


cf_ns.delete_custom_field_response = function(data) {

  // check the dialog is still open. If not, just forget it
  var is_open = $("#delete_group_popup").css("display");
  if (is_open != "block") {
    return;
  }

  ft.dialog_activity_icon($("#delete_group_popup"), "hide");
  var message = "";
  if (data.length > 0) {
    message = g.messages["confirm_delete_field_type_in_use"];
  } else {
    message = g.messages["confirm_delete_field_type"];
  }

  var html = "<table cellspacing=\"0\" cellpadding=\"0\">"
    + "<tr>"
       + "<td valign=\"top\"><span class=\"popup_icon popup_type_warning\"></span></td>"
       + "<td>" + message + "</td>"
    + "</tr>"
    + "</table>";

  $("#delete_group_popup").html(html).dialog({
    buttons: [{
      text:  g.messages["word_yes"],
      click: function() {
        $("#custom_fields_form").append("<input type=\"hidden\" name=\"delete_field_type\" value=\"" + cf_ns.__current_field_type_id + "\" />").trigger("submit");
      }
    },
    {
      text:  g.messages["word_no"],
      click: function() {
        $(this).dialog("close");
      }
    }]
  });
}


/**
 * This function is called in the Add Form process, and on the Edit Form -> main tab. It dynamically
 * adds rows to the "Form URLs" section, letting the user add as many page URLs as their form contains.
 */
cf_ns.add_shared_resource = function() {
  var li1 = $("<li class=\"col1 sort_col\"></li>");
  var li2 = $("<li class=\"col2\"><input type=\"text\" name=\"resources[]\" /></li>");
  var li3 = $("<li class=\"col3 colN del\"></li>");
  var ul  = $("<ul></ul>").append(ft.group_nodes([li1, li2, li3]));

  var hidden_sort_field = $("<input type=\"hidden\" value=\"\" class=\"sr_order\">");
  var clr = $("<div class=\"clear\"></div>");
  var row_group  = $("<div class=\"row_group\"></div>").append(ft.group_nodes([hidden_sort_field, ul, clr]));

  var html = sortable_ns.get_sortable_row_markup({row_group: row_group, is_grouped: false });

  $(".shared_resources_included_files .rows").append(html);
  sortable_ns.reorder_rows($(".shared_resources_included_files"), true);

  return false;
}


cf_ns.select_validation_rule = function(e) {
  var rule = $(e.target).val();
  if (rule == "function") {
    $("#custom_function_settings").removeClass("hidden");
    $("#standard_settings").addClass("hidden");
  } else {
    $("#custom_function_settings").addClass("hidden");
    $("#standard_settings").removeClass("hidden");
  }
}


cf_ns.delete_validation_rule = function(el) {
  var rule_id = $(el).closest(".row_group").find(".sr_order").val();
  ft.create_dialog({
	title:   g.messages["phrase_please_confirm"],
	content: g.messages["confirm_delete_validation_rule"],
	popup_type: "warning",
	buttons: [
      {
        text: g.messages["word_yes"],
        click: function() {
          window.location = "?delete=" + rule_id;
        }
      },
      {
        text: g.messages["word_no"],
        click: function() { $(this).dialog("close"); }
      }
    ]
  })
}
