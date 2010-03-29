<?php

class dmBotAdminActions extends dmAdminBaseActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->form = $this->getService('dm_bot_configuration_form');

    $this->form->setDefaults($this->getServiceContainer()->getParameter('dm_bot.options'));

    if($request->hasParameter($this->form->getName()) && $this->form->bindAndValid($request))
    {
      $this->bot = $this->getServiceContainer()
      ->setParameter('dm_bot.options', $this->form->getValues())
      ->getService('dm_bot')
      ->setBaseUrl($request->getAbsoluteUrlRoot())
      ->init();
    }
  }
}