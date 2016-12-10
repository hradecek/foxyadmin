<?php

namespace Foxytouch\Article\Tests\Unit;

use Foxytouch\Article\Repositories\Contracts\ArticleRepository;
use Foxytouch\Article\Repositories\Contracts\CategoryRepository;
use Foxytouch\Article\Repositories\Contracts\ArticleStatusRepository;

use Foxytouch\Tests\TestCase;

class ArticleCrudTestCase extends TestCase
{
    /**
     * @var ArticleStatusRepository
     */
    private $status;
    
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->status = $this->app->make(ArticleStatusRepository::class);
        $this->articleRepository = $this->app->make(ArticleRepository::class);
        $this->categoryRepository = $this->app->make(CategoryRepository::class);
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
    public function testCreateValidArticleWithoutThumb()
    {
        $formRequest = $this->getMockRequest();
        $this->createAndAssert($formRequest);
    }
    
    /**
     * Given
     * When
     * then
     */
    public function testCreateValidArticleWithThumb()
    {
        $formRequest = $this->getMockRequest();
        $this->createAndAssert($formRequest);
    }
    
    private function getMockRequest($hasThumb = false)
    {
        $formRequest = [
            'slug'  => $this->faker->userName,
            'title' => $this->faker->userName,
            'content' => $this->faker->paragraph,
        ];

        if ($hasThumb) {
            $picture = $this->faker->image(sys_get_temp_dir(), 300, 300);
            $formRequest['thumb_uri'] = $picture;
        }
        
        return $formRequest;
    }

    private function createAndAssert($formRequest)
    {
        $createdArticle = $this->articleRepository->create($formRequest);

        $this->assertTrue(null != $createdArticle, 'Article was not persisted');
        $this->assertSameArticle($formRequest, $createdArticle);
    }

    private function assertSameArticle(array $expected, $actual)
    {
        $this->assertEquals($expected['slug'], $actual->slug);
        $this->assertEquals($expected['title'], $actual->title);
        $this->assertEquals($expected['content'], $actual->content);
        $this->assertEquals($expected['thumb_uri'], $actual->thumb_uri);
    }
}