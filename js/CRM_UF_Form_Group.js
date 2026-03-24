CRM.$(function($) {
  // Add id attribute to bhfe table, so it's easy to reference later.
  CRM.$('input#cdashtabs_is_cdash').closest('table').addClass('cdashtabs-bhfe-table');
  // Move bhfe form elements into main form.
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#cdashtabs_is_edit').closest('tr'));
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#cdashtabs_is_show_pre_post').closest('tr'));
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('select#cdashtabs_cdash_contact_type').closest('tr'));
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#cdashtabs_is_cdash').closest('tr'));
  // Remove the bhfe table, but only if it's empty.
  if ($('table.cdashtabs-bhfe-table tr').length == 0) {
    $('table.cdashtabs-bhfe-table').remove();
  }

  /**
   * onChange handler for cdashtabs_is_cdash checkbox.
   */
  var isCdashChange = function isCdashChange() {
    if (CRM.$('input#cdashtabs_is_cdash').is(':checked')) {
      CRM.$('select#cdashtabs_cdash_contact_type').closest('tr').show();
      CRM.$('input#cdashtabs_is_show_pre_post').closest('tr').show();
      CRM.$('input#cdashtabs_is_edit').closest('tr').show();
    }
    else {
      CRM.$('select#cdashtabs_cdash_contact_type').closest('tr').hide();
      CRM.$('input#cdashtabs_is_show_pre_post').closest('tr').hide();
      CRM.$('input#cdashtabs_is_edit').closest('tr').hide();
    }
  };

  CRM.$('input#cdashtabs_is_cdash').change(isCdashChange);
  isCdashChange();
});
