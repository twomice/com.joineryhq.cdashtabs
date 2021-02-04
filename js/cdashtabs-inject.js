CRM.$(function($){
  // This code needs to run even when we're not configured to use tab buttons on the dashboard,
  // thus it's separate from cdashtabs.js.

  // Check if any sections have been injected on the page.
  // this would have been done in cdashtabs_civicrm_alterContent().
  // (We test length here on a hunch that it may improve performance.)
  if ($('div.cdash-inject-section').length) {
    // For each injected section, move it into the main table.
    $('div.cdash-inject-section').each(function(){
      var injectHtml = $('> table > tbody' ,this).html();
      $('.dashboard-elements > tbody').append(injectHtml);
    });
  }

  if (CRM.vars.cdashtabs.dashboardLink) {
    $(window).load(function(){
      $('#crm-container').before('<a href="' + CRM.vars.cdashtabs.dashboardLink + '">Back to my dashboard</a>')
    });
  }
});
