<?php

echo _tag('div.dm_box.little.mt10',
  _tag('div.dm_box_inner',
    $form->render('.dm_form.list method=get')
  )
);

if(isset($bot))
{
  echo _tag('div.dm_box.medium.mt10.dm_bot',
    _tag('p.title', 'Browsing '.$bot->getNbPages().' pages').
    _tag('div.dm_box_inner.dm_data.dm_bot_urls',
      _tag('div.control_bar.clearfix',
        _tag('button.start_stop', 'Start').
        _tag('p', _tag('span.nb_completed', 0).'/'.$bot->getNbPages().' pages').
        _tag('p', _tag('span.nb_errors', 0).' errors').
        _tag('p', _tag('span.time', '-').'ms/page')
      ).
      _tag('div.progress.ui-corner-all', _tag('div.progress_bar')._tag('div.progress_text', '0%')).
      _tag('div.urls_container', $bot->render())
    )
  );
}