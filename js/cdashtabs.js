CRM.$(function($){
  // This code runs only if we're configured to use tabs on the dashboard.

  // Get the main table of the user dashboard
  var mainTable = $('table.dashboard-elements');

  // Create tab button container
  mainTable.before('<div id="cdashtabsButtons"></div>');

  // Shortand variable for tab button container
  var cdashtabsButtonsDiv = $('#cdashtabsButtons');

  // Create a tab button for each configured section.
  for (var i in CRM.vars.cdashtabs.tabButtons) {
    // Append button with data-target and label
    cdashtabsButtonsDiv.append('<button data-target="crm-dashboard-' + CRM.vars.cdashtabs.tabButtons[i].cssClass + '">' + CRM.vars.cdashtabs.tabButtons[i].tabLabel + '</button>');
  }

  // Add button show/hide row functionality when clicked
  $(document).on('click', '#cdashtabsButtons button', function(e) {
    // Button is inside form element, so need to prevent form submission:
    e.preventDefault();

    // Hide all rows in the main table.
    mainTable.children('tbody').children('tr').hide();

    // Display the relevant section.
    var sectionTrClassName = $(this).data('target');
    mainTable.find('.' + sectionTrClassName).show();

    // Mark all tab buttons as inactive.
    cdashtabsButtonsDiv.find('button').removeClass('cdashtabs-is-active');
    // Mark this tab button as active.
    $(this).addClass('cdashtabs-is-active');
  });

  // Show first row element.
  cdashtabsButtonsDiv.find('button:first-child').trigger('click');
});
