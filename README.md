## Custom Fields module

The Custom Fields module lets you customize and create those very field types that you see in your forms: textboxes, 
textareas, etc. This is an incredibly powerful module. In the immortal words of Keanu Reeves: "Whoaahhh".

The Custom Fields module is special. It was designed alongside version 2.1.0 of the Form Tools Core to provide a new
level of flexibility in the field types and what options are available to configure them. Rather than distributing
this functionality with the main release, we decided that it would lead to a lot of confusion and people potentially
breaking their installations. So instead, the Custom Fields module is released separately, only for people who need that
advanced functionality.

In Form Tools 2.0.x, there was a hardcoded list of available field types. These were all the common fields types that
you've seen in any web form: textboxes, password fields, textareas, radio buttons, checkboxes, dropdowns, multi-select
dropdowns, file upload fields and a TinyMCE WYSIWYG field. Although these field types covered most typical needs, they
were inconsistently implemented (e.g. the date picker could only be used by the Core's Submission Date field) and
couldn't be easily changed. With the up and coming HTML5 field types (dates, colour pickers, sliders and so on), we
decided to devote 2.1.x to restructuring the Core to be flexible in how those field types are displayed, saved,
processed and managed. The end result was the Custom Fields module.

### Documentation

- [https://docs.formtools.org/modules/custom_fields/](https://docs.formtools.org/modules/custom_fields/)


### Other Links

- [Available Form Tools modules](https://modules.formtools.org/)
- [About Form Tools modules](https://docs.formtools.org/userdoc/modules/) 
- [Installation instructions](https://docs.formtools.org/userdoc/modules/installing/)
- [Upgrading](https://docs.formtools.org/userdoc/modules/upgrading/)
