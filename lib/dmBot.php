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
    $nbPages = $this->getNbPages();
    
    $table = $this->helper->table($options)
    ->useStrip(true)
    ->head('#', 'Url', 'Status', 'Time');

    foreach($this->getPages() as $index => $page)
    {
      $url = $this->getPageUrl($page);
      $statusCode = $this->getPageStatusCode($page);
      
      $table->body(
        ($index+1).'/'.$nbPages,
        $this->helper->tag('span.link', array('data-status-code' => $statusCode),
          $this->helper->link($url)->text($url)
        ),
        '<span class="status"></span>',
        '<span class="time"></span>'
      );
    }

    return $table->render();
  }

  protected function getPageUrl(DmPage $page)
  {
    return $this->baseUrl.'/'.$page->_getI18n('slug');
  }

  protected function getPageStatusCode(DmPage $page)
  {
    $statusCode = 200;
    
    if('main' === $page->get('module'))
    {
      if('error404' === $page->get('action'))
      {
        $statusCode = 404;
      }
      elseif('signin' === $page->get('action'))
      {
        $statusCode = 401;
      }
    }

    return $statusCode;
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