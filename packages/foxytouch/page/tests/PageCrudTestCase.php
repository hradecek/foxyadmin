<?php

namespace Foxytouch\Article\Tests\Unit;

use Foxytouch\Tests\TestCase;
use Foxytouch\Page\Repositories\Contracts\PageRepository;

class PageCrudTestCase extends TestCase
{
    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function setUp()
    {
        parent::setUp();

        $this->pageRepository = $this->app->make(PageRepository::class);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Given
     * When
     * then
     */
    public function testCreateValidPage()
    {
    }
}