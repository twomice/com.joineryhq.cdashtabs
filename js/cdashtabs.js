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
    var cssClass = CRM.vars.cdashtabs.tabButtons[i].cssClass;
    if (!$('tr.crm-dashboard-' + cssClass).length){
      // https://github.com/twomice/com.joineryhq.cdashtabs/issues/8 :
      // The content for this configured section is not contained in the dashboard.
      // Therefore, don't create a button for it.
      continue;
    }
    cdashtabsButtonsDiv.append('<button id="cdashtabs-section-' + cssClass + '" data-cdashtabs-section-id="' + cssClass + '">' + CRM.vars.cdashtabs.tabButtons[i].tabLabel + '</button>');
  }

  // Add button show/hide row functionality when clicked
  $(document).on('click', '#cdashtabsButtons button', function(e) {
    // Button is inside form element, so need to prevent form submission:
    e.preventDefault();

    // Hide all rows in the main table.
    mainTable.children('tbody').children('tr').hide();

    // Shorthand variable for button section identifier
    var buttonSectionId = $(this).data('cdashtabs-section-id');

    // Display the relevant section.
    var sectionTrClassName = 'crm-dashboard-' + buttonSectionId;

    mainTable.find('.' + sectionTrClassName).show();

    // Mark all tab buttons as inactive.
    cdashtabsButtonsDiv.find('button').removeClass('cdashtabs-is-active');
    // Mark this tab button as active.
    $(this).addClass('cdashtabs-is-active');
    // Add section-X to anchor component in browser address bar.
    CRM.$(location).attr('hash', 'section-' + buttonSectionId);
  });

  // If we're requested to reveal a specific section, do it.
  // Note that if the requested section button doesn't exist, nothing will be selected.
  var requestedSectionIdentifier = CRM.$(location).attr('hash').replace(/^#/, '');
  if (requestedSectionIdentifier) {
    var requestedSectionButtonId = 'cdashtabs-' + requestedSectionIdentifier;
    cdashtabsButtonsDiv.find('button#' + requestedSectionButtonId).trigger('click');
  }
  // If no button is selected, we need to select something else.
  if (!$('button.cdashtabs-is-active').length) {
    // Just reveal the first section.
    cdashtabsButtonsDiv.find('button:first-child').trigger('click');
  }
});
