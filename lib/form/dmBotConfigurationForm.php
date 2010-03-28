<?php

class dmBotConfigurationForm extends dmForm
{

  public function configure()
  {
    $this->widgetSchema['limit'] = new sfWidgetFormChoice(array(
      'choices' => dmArray::valueToKey($this->getLimits())
    ));
    $this->validatorSchema['limit'] = new sfValidatorChoice(array(
      'choices' => $this->getLimits()
    ));
  }
  
  public function renderSubmitTag($value = 'submit', $attributes = array())
  {
    return parent::renderSubmitTag('Browse the website', $attributes);
  }

  protected function getLimits()
  {
    return array(1, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000);
  }
}