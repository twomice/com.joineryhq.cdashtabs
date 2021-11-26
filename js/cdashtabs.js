// Immediately hide all section-anchor links, so that browser anchor behavior
// does not cause page scrolling. Without this, the browser will scroll to the
// named anchor immediately, such that the page may be inappropriately scrolled
// down even after sections are hidden behind buttons. (This undesirable behavior
// was visible when being redirected to dashboard after submitting a profile.)
CRM.$('a.cdashtabs-section-anchor').hide();

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
  var requestedSectionIdentifier = CRM.$(location).attr('hash').replace(/^#/, '');
  if (requestedSectionIdentifier) {
    var requestedSectionButtonId = 'cdashtabs-' + requestedSectionIdentifier;
    cdashtabsButtonsDiv.find('button#' + requestedSectionButtonId).trigger('click');
  }
  else {
    // Otherwise, just reveal the first section.
    cdashtabsButtonsDiv.find('button:first-child').trigger('click');
  }
});
