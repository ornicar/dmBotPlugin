(function($)
{
  $(function()
  {
    if(!$('div.dm_bot').length)
    {
      return;
    }
    var $element = $('div.dm_bot'),
    $trs = $element.find('.dm_bot_urls tbody tr'),
    $control = $element.find('div.control_bar'),
    $progressBar = $element.find('div.progress_bar'),
    $progressText = $element.find('div.progress_text'),
    $button = $element.find('button.start_stop'),
    nb = $trs.length,
    index = 0;
    run = false,
    nbErrors = 0,
    totalTime = 0;
    complete = function(isOk, status, time)
    {
      time = new Date().valueOf() - start;

      $('span.status', $trs[index])
      .removeClass('s16_gear')
      .addClass('s16_'+(isOk ? 'tick' : 'error'))
      .text(status);

      $('span.time', $trs[index]).text(time+"ms");

      index++;

      $progressBar.animate({width: Math.round(((index+1)/nb)*100)+"%"}, time*3/4);
      $progressText.text(Math.round((index/nb)*100)+"%");

      $control.find('span.nb_completed').text(index);
      if(!isOk)
      {
        nbErrors++;
        $control.find('span.nb_errors').text(nbErrors);
      }
      totalTime += time;
      $control.find('span.time').text(Math.round(totalTime/index));
      
      $trs.removeClass('current');

      if(index < nb)
      {
        if(run)
        {
          process();
        }
      }
      else
      {
        end();
      }
    },
    process = function()
    {
      $($trs[index]).addClass('current');
      
      $('span.status', $trs[index]).addClass('s16 s16_gear').text(' ');

      url = $('span.link', $trs[index]).text();
      start = new Date().valueOf();
      $.ajax({
        cache:    true,
        url:      url+"?dm_bot="+start,
        success:  function()
        {
          complete(true, 200, start);
        },
        error:  function(XMLHttpRequest, textStatus, errorThrown)
        {
          isOk = XMLHttpRequest.status == $('span.link', $trs[index]).attr('data-status-code');
          complete(isOk, XMLHttpRequest.status, start);
        }
      })
    },
    end = function()
    {
      $button.text('Reset').unbind('click').click(function()
      {
        window.location.reload();
      });
      
      $.ajaxSetup({
        data:     $.dm.defaults.ajaxData
      });
    };

    $.ajaxSetup({
      data:     []
    });

    $button.click(function()
    {
      run = !run;
      $(this).text(run ? 'Stop' : 'Resume');
      if(run)
      {
        process();
      }
    });
  });
})(jQuery);