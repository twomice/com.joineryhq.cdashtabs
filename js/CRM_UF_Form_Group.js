CRM.$(function($) {
  // Add id attribute to bhfe table, so it's easy to reference later.
  CRM.$('input#is_cdash').closest('table').attr('id', 'bhfe-table');
  // Move bhfe form elements into main form.
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#is_show_pre_post').closest('tr'));
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#is_cdash').closest('tr'));
  // Remove bhfe table.
  CRM.$('table#bhfe-table').remove();

  /**
   * onChange handler for is_cdash checkbox.
   */
  var isCdashChange = function isCdashChange() {
    if (CRM.$('input#is_cdash').is(':checked')) {
      CRM.$('input#is_show_pre_post').closest('tr').show();
    }
    else {
      CRM.$('input#is_show_pre_post').closest('tr').hide();
    }
  };

  CRM.$('input#is_cdash').change(isCdashChange);
  isCdashChange();
});