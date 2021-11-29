CRM.$(function($){
  // This code needs to run even when we're not configured to use tab buttons on the dashboard,
  // thus it's separate from cdashtabs.js.

  // Check if any sections have been injected on the page.
  // this would have been done in cdashtabs_civicrm_alterContent().
  // (We test length here on a hunch that it may improve performance.)
  if ($('div.cdashtabs-inject-section').length) {
    // For each injected section, move it into the main table.
    // We do this with append(html) because simply using append() does not yield
    // correct formatting.
    $('div.cdashtabs-inject-section').each(function(){
      var elSection = CRM.$('> table > tbody' ,this);
      var injectHtml = elSection.html();
      $('.dashboard-elements > tbody').append(injectHtml);
      // Now that we've copied the html, remove the original section element from the DOM.
      elSection.remove();
    });
  }

  if (CRM.vars.cdashtabs && CRM.vars.cdashtabs.dashboardLink && !$('#cdashtabs-mydashboard-link').length) {
    $('#crm-container').before('<a id="cdashtabs-mydashboard-link" href="' + CRM.vars.cdashtabs.dashboardLink + '">Back to my dashboard</a>');
  }
});
