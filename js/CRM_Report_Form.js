CRM.$(function($) {
  CRM.$('input#is_cdash').closest('table').attr('id', 'bhfe-table');
  CRM.$('tr.crm-report-instanceForm-form-block-addToDashboard').after(CRM.$('input#is_cdash').closest('tr').addClass('crm-report-instanceForm-form-block-isCdash'));
  CRM.$('table#bhfe-table').remove();
});
