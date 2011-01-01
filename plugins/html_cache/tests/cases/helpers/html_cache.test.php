<?php
App::import('Helper', array('HtmlCache.HtmlCache'));
App::import('Core', array('View'));

class HtmlCacheTestCase extends CakeTestCase {
  public $View = null;
  public $www_root = null;

  public function startCase() {
    $this->www_root = ROOT . DS . 'app' . DS . 'plugins' . DS . 'html_cache' . DS . 'tests' . DS . 'test_app' . DS . 'webroot' . DS;
    $controller = null;
    $this->View = new View($controller);
    $this->View->loaded['HtmlCache'] = new HtmlCacheHelper(array('test_mode' => true, 'www_root' => $this->www_root));
    $this->View->loaded['HtmlCache']->here = '/posts';
  }

  public function endCase() {
    $Folder = new Folder();
    $Folder->delete($this->www_root . 'cache');
  }

  public function testInstances() {
    $this->assertTrue(is_a($this->View, 'View'));
    $this->assertTrue(is_a(ClassRegistry::getObject('view'), 'View'));
  }

  public function testWriteCache() {
    $expected = <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
    <title>HtmlCache Test Title</title>
</head>
<body>
    HtmlCache Test Body
</body>
</html>
END;

    $this->View->output = $expected;
    $this->View->_triggerHelpers('afterLayout');
    
    $path = $this->www_root . 'cache' . DS . 'posts' . DS . 'index.html';
    $this->assertTrue(file_exists($path));
    $cached = file_get_contents($path);
    $this->assertEqual($expected, $cached);
  }
}