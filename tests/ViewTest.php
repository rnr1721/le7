<?php

use Core\Interfaces\ViewTopology;
use Core\Interfaces\ViewAdapter;
use Core\View\Php\PhpViewAdapter;
use Core\View\ViewTopologyGeneric;
use Core\View\WebPageGeneric;
use Core\Testing\MegaFactory;
use Psr\SimpleCache\CacheInterface;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class ViewTest extends PHPUnit\Framework\TestCase
{

    private string $testsDirectory;
    private MegaFactory $megaFactory;

    protected function setUp(): void
    {
        $this->testsDirectory = getcwd() . DIRECTORY_SEPARATOR . 'tests';
        $this->megaFactory = new MegaFactory($this->testsDirectory);
    }

    public function testPhp()
    {
        $adapter = $this->getPhpAdapter();
        $view = $adapter->getView();
        $view->assign('one', 'one_var');
        $view->assign('two', 'two_var');
        $response = $view->render('layout.phtml', [], 201, ['myheader' => 'testheader']);
        $this->assertEquals('testheader', $response->getHeaderLine('myheader'));
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('one_vartwo_varhttps://example.com/jsparthttps://example.com/theme', (string) $response->getBody());
    }

    public function testPhpWriteCache()
    {
        $cache = $this->megaFactory->getCache()->getFileCache();
        $adapter = $this->getPhpAdapter($cache);
        $view = $adapter->getView();
        $data = [
            'one' => 'one_var',
            'two' => 'two_var'
        ];
        $response = $view->render('layout.phtml', $data, 200, [], 0);
        $this->assertEquals('one_vartwo_varhttps://example.com/jsparthttps://example.com/theme', (string) $response->getBody());

        return $cache;
    }

    /**
     * 
     * @depends testPhpWriteCache
     */
    public function testPhpReadCache(CacheInterface $cache)
    {
        $adapter = $this->getPhpAdapter($cache);
        $view = $adapter->getView();
        $cachedResponse = $view->renderFromCache();
        $this->assertEquals(200, $cachedResponse->getStatusCode());
        $this->assertEquals('one_vartwo_varhttps://example.com/jsparthttps://example.com/theme', (string) $cachedResponse->getBody());
        $this->assertTrue($cachedResponse->hasHeader('Content-Type'));
        $this->assertEquals('text/html', $cachedResponse->getHeaderLine('Content-Type'));
    }

    public function getPhpAdapter(CacheInterface $cache = null): ViewAdapter
    {
        if (empty($cache)) {
            $cache = $this->megaFactory->getCache()->getFileCache();
        }

        $viewTopology = $this->getViewTopology();
        $webPage = new WebPageGeneric($viewTopology);
        $request = $this->megaFactory->getServer()->getServerRequest('https://example.com/page/open', 'GET');
        $responseFactory = $this->megaFactory->getServer()->getResponseFactory();

        return new PhpViewAdapter($viewTopology, $webPage, $request, $responseFactory, $cache);
    }

    public function getViewTopology(): ViewTopology
    {
        $viewTopology = new ViewTopologyGeneric();
        $viewTopology->setBaseUrl('https://example.com')
                ->setCssUrl('https://example.com/css')
                ->setFontsUrl('https://example.com/fonts')
                ->setImagesUrl('https://https://example.com/images')
                ->setJsUrl('https://example.com/js')
                ->setLibsUrl('https://example.com/libs')
                ->setThemeUrl('https://example.com/theme')
                ->setTemplatePath($this->testsDirectory . DIRECTORY_SEPARATOR . 'mock_templates');
        return $viewTopology;
    }

}
