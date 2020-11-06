CRM.$(function($) {
  CRM.$('input#is_cdash').closest('table').attr('id', 'bhfe-table');
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#is_show_pre_post').closest('tr'));
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#is_cdash').closest('tr'));
  CRM.$('table#bhfe-table').remove();

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