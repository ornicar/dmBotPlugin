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
      'limit'       => 20,
      'only_active' => true
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
    ->useStrip(false)
    ->head('#', 'Url', 'Status', 'Time');

    foreach($this->getPages() as $index => $page)
    {
      $url = $this->getPageUrl($page);
      $statusCode = $this->getPageStatusCode($page);
      
      $table->body(
        ($index+1),
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
      if('error404' === $page->get('action') || !$page->get('is_active'))
      {
        $statusCode = 404;
      }
      elseif('signin' === $page->get('action') || $page->get('is_secure'))
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
    $query = dmDb::query('DmPage p')->withI18n();

    if($this->getOption('limit'))
    {
      $query->limit($this->getOption('limit'));
    }
    
    if($this->getOption('only_active'))
    {
      $query->addWhere('pTranslation.is_active = ?', true);
    }

    foreach(array('slug', 'name', 'title') as $dbField)
    {
      if($this->getOption($dbField.'_pattern'))
      {
        $query->addWhere('pTranslation.'.$dbField.' LIKE ?', str_replace('*', '%', $this->getOption($dbField.'_pattern')));
      }
    }

    return $query;
  }

  public function __toString()
  {
    return $this->render();
  }
}