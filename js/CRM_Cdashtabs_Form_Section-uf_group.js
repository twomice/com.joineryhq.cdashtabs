CRM.$(function($) {
  /**
   * onChange handler for is_cdash checkbox.
   */
  var isCdashChange = function isCdashChange() {
    if (CRM.$('input#is_cdash').is(':checked')) {
      CRM.$('select#cdash_contact_type').closest('tr').show();
      CRM.$('input#is_show_pre_post').closest('tr').show();
    }
    else {
      CRM.$('select#cdash_contact_type').closest('tr').hide();
      CRM.$('input#is_show_pre_post').closest('tr').hide();
    }
  };

  CRM.$('input#is_cdash').change(isCdashChange);
  isCdashChange();
});
