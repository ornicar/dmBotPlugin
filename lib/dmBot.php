<?php

class dmBot extends dmConfigurable
{
  protected
  $helper,
  $baseUrl,
  $pages;
  
  public function __construct(dmHelper $helper,  array $options)
  {
    $this->helper = $helper;
    
    $this->configure($options);
  }

  public function getDefaultOptions()
  {
    return array(
      'limit' => 10
    );
  }

  public function setBaseUrl($url)
  {
    $this->baseUrl = $url;

    return $this;
  }

  public function init()
  {
    $this->pages = $this->getQuery()->fetchRecords();

    return $this;
  }

  public function render($options = array())
  {
    $table = $this->helper->table($options)
    ->useStrip(true)
    ->head('Url', 'Status');

    foreach($this->getPages() as $page)
    {
      $table->body($this->baseUrl.'/'.$page->_getI18n('slug'), '<span class="status"></span>');
    }

    return $table->render();
  }

  public function getPages()
  {
    return $this->pages;
  }

  public function getNbPages()
  {
    return $this->pages->count();
  }

  protected function getQuery()
  {
    return dmDb::query('DmPage p')
    ->withI18n()
    ->limit($this->getOption('limit'));
  }

  public function __toString()
  {
    return $this->render();
  }
}