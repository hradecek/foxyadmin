<?php

namespace Foxytouch\Tests;

use Mockery;
use Faker\Factory;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase {

    /**
     * The base URL to use while testing the application.
     * 
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    
    protected $faker;

    /**
     * TODO: Test temp dir
     */
    
    /**
     * Creates the application.
     * 
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate', ['--path' => 'packages/foxytouch/user/src/database/migrations/']); /* TODO: */
        $this->faker = Factory::create();
    }
    
    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    protected function createMock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }
}
