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

    $this->widgetSchema['only_active'] = new sfWidgetFormInputCheckbox(array(
      'label' => 'Only active pages'
    ));
    $this->validatorSchema['only_active'] = new sfValidatorBoolean();

    foreach(array('slug', 'name', 'title') as $dbField)
    {
      $this->widgetSchema[$dbField.'_pattern'] = new sfWidgetFormInputText(array(
        'label' => dmString::humanize($dbField)
      ));
      $this->widgetSchema->setHelp($dbField.'_pattern', 'Wildcard * accepted');
      $this->validatorSchema[$dbField.'_pattern'] = new sfValidatorString(array(
        'required' => false
      ));
    }
  }
  
  public function renderSubmitTag($value = 'submit', $attributes = array())
  {
    return parent::renderSubmitTag('Find pages', $attributes);
  }

  protected function getLimits()
  {
    return array(1, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000);
  }
}