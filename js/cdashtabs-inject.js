CRM.$(function($){
  if ($('.cdash-inject-list').length) {
    $('.cdash-inject-list').each(function(){
      var injectHtml = $('> table > tbody' ,this).html();
      $('.dashboard-elements > tbody').append(injectHtml);
    });
  }
});
