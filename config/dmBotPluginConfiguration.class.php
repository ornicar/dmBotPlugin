<?php

class dmBotPluginConfiguration extends sfPluginConfiguration
{
  public function configure()
  {
    $this->dispatcher->connect('context.load_factories', array($this,'listenToContextFactoriesLoaded'));
  }

  public function listenToContextFactoriesLoaded(sfEvent $e)
  {
    if('front' === sfConfig::get('sf_app'))
    {
      $request = $e->getSubject()->getRequest();
      
      if($request->hasParameter('dm_bot'))
      {
        // deauthenticate the user
        sfConfig::set('dm_security_remember_cookie_name', 'disabled_remember_me_'.dmString::random());
        $e->getSubject()->getUser()->setAuthenticated(false);

        // mark the response as not XHR
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // remove "dm_bot" GET parameter
        $getParameters = $request->getGetParameters();
        unset($getParameters['dm_bot']);
        $request->setGetParameters($getParameters);
        $request->getParameterHolder()->remove('dm_bot');
      }
    }
  }
}