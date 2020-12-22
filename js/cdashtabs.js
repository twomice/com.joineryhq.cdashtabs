CRM.$(function($){
  // Get the main table of the user dashboard
  var mainTable = $('table.dashboard-elements');

  // Create tab button main element
  mainTable.before('<div id="cdashtabsButtons"></div>');

  // Get tab button main element
  var cdashtabsButtonsDiv = $('#cdashtabsButtons');

  for (var i in CRM.vars.cdashtabs.tabButtons) {
    // Append button with data-target and label
    cdashtabsButtonsDiv.append('<button data-target="crm-dashboard-' + CRM.vars.cdashtabs.tabButtons[i].cssClass + '">' + CRM.vars.cdashtabs.tabButtons[i].tabLabel + '</button>');
  }

  // Add button show/hide row functionality when clicked
  $(document).on('click', '#cdashtabsButtons button', function(e) {
    e.preventDefault();
    var sectionTr = $(this).data('target');
    mainTable.children('tbody').children('tr').hide();
    mainTable.find('.' + sectionTr).show();
    cdashtabsButtonsDiv.find('button').removeClass('cdashtabs-is-active');
    $(this).addClass('cdashtabs-is-active');
  });

  // Show first row element
  cdashtabsButtonsDiv.find('button:first-child').trigger('click');
});
