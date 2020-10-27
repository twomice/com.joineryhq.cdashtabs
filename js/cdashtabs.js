CRM.$(function($){
  // Get the main table of the user dashboard
  var $mainTable = $('table.dashboard-elements');

  // Create tab button main element
  $mainTable.before('<div class="cdashtabs"></div>');

  // Get tab button main element
  var $cdashtabs = $('.cdashtabs');

  // Hide and get each row class and label for the button target and label
  $mainTable.find('> tbody > tr').hide().each(function(){
    var cTrClass = $(this).attr('class'),
        cTrLabel = $('td:first-child > div:first-child', this).text(),
        btnLabel = cTrLabel.replace('Your ', '');

    // Append button with data-target and label
    $cdashtabs.append('<button data-target="' + cTrClass + '">' + btnLabel + '</button>');
  });

  // Add button show/hide row functionality when clicked
  $(document).on('click', '.cdashtabs button', function(e) {
    e.preventDefault();
    var showTr = $(this).data('target');
    $mainTable.find('tr').hide();
    $mainTable.find('.' + showTr).show();
    $cdashtabs.find('button').removeClass('is-active');
    $(this).addClass('is-active');
  });

  // Show first row element
  $cdashtabs.find('button:first-child').trigger('click');
});
