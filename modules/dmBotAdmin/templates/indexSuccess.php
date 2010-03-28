<?php

echo _tag('div.dm_box.medium.mt10',
  _tag('div.dm_box_inner',
    $form->render('.dm_form.list method=get')
  )
);

if(isset($bot))
{
  echo _tag('div.dm_box.medium.mt10',
    _tag('p.title', 'Browsing '.$bot->getNbPages().' pages').
    _tag('div.dm_box_inner.dm_data', $bot->render('.dm_bot_urls'))
  );
}