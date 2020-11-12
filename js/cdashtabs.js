CRM.$(function($){
  // Get the main table of the user dashboard
  var mainTable = $('table.dashboard-elements');

  // Create tab button main element
  mainTable.before('<div class="cdashtabs"></div>');

  // Get tab button main element
  var cdashtabs = $('.cdashtabs');

  for (i in CRM.vars.cdashtabs.options) {
    // Append button with data-target and label
    cdashtabs.append('<button data-target="crm-dashboard-' + CRM.vars.cdashtabs.options[i].class + '">' + CRM.vars.cdashtabs.options[i].name + '</button>');
  }

  // Add button show/hide row functionality when clicked
  $(document).on('click', '.cdashtabs button', function(e) {
    e.preventDefault();
    var showTr = $(this).data('target');
    mainTable.children('tbody').children('tr').hide();
    mainTable.find('.' + showTr).show();
    cdashtabs.find('button').removeClass('is-active');
    $(this).addClass('is-active');
  });

  // Show first row element
  cdashtabs.find('button:first-child').trigger('click');
});
