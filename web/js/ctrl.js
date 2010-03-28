(function($)
{
  $(function()
  {
    var $trs = $('.dm_bot_urls tbody tr'), nb = $trs.length;
    
    if(nb)
    {
      var
      complete = function(index, status)
      {
        $span = $('td:last span', $trs[index])
        .removeClass('s16_gear');

        $span.addClass('s16_'+(status == 'ok' ? 'tick' : 'error'));

        if(index < nb)
        {
          setTimeout(function(){process(index+1);}, 2000);
        }
      },
      process = function(index)
      {
        $('td:last span', $trs[index]).addClass('s16block s16_gear');
        
        $.ajax({
          url:      $('td:first', $trs[index]).text(),
          success:  function()
          {
            complete(index, 'ok');
          },
          error:  function()
          {
            complete(index, 'error');
          }
        })
      };

      process(0);
    }
  });
})(jQuery);