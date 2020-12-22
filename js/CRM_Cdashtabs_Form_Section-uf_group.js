CRM.$(function($) {
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