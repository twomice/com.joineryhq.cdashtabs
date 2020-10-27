CRM.$(function($) {
  CRM.$('input#is_cdash').closest('table').attr('id', 'bhfe-table');
  CRM.$('tr.crm-ufgroup-form-block-uf_group_type').after(CRM.$('input#is_cdash').closest('tr'));  
  CRM.$('table#bhfe-table').remove();

})