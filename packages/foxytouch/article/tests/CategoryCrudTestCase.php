<?php

namespace Foxytouch\Article\Tests\Unit;

use Foxytouch\Tests\TestCase;
use Foxytouch\Article\Repositories\Contracts\CategoryRepository;

class CategoryCrudTestCase extends TestCase
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = $this->app->make(CategoryRepository::class);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Given category does not exists.
     * When request for creation is made,
     * then category is created successfully.
     */
    public function testCreateValidCategory()
    {
        $formRequest = $this->getMockRequest();

        $createdCategory = $this->categoryRepository->create($formRequest);
        $this->assertTrue(null != $createdCategory->id, 'Category was not persisted');
        $this->assertSameCategory($formRequest, $createdCategory);
    }

    private function getMockRequest()
    {
        return [
            'name' => $this->faker->unique()->userName,
            'description' => $this->faker->sentence
        ];
    }

    private function assertSameCategory(array $expected, $actual)
    {
        $this->assertEquals($expected['name'], $actual->name, 'Name should be same');
        $this->assertEquals($expected['description'], $actual->description, 'Description should be same');
    }
}
