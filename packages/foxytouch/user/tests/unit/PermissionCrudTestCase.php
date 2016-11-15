<?php

use Foxytouch\Tests\TestCase;
use Foxytouch\User\Models\Permission;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

/**
 * Class PermissionCrudTestCase
 * 
 * @package \Foxytouch\Tests\TestCase
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class PermissionCrudTestCase extends TestCase
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    
    public function setUp()
    {
        parent::setUp();

        $this->permissionRepository = $this->app->make(PermissionRepository::class);
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * Given permission does not exists.
     * When request for creation is sent,
     * then permission is created successfully.
     */
    public function testCreateValidPermission()
    {
        $formRequest = $this->getMockRequest();

        $createdPermission = $this->permissionRepository->create($formRequest);
        $this->assertTrue(null != $createdPermission->id, 'Permission was not persisted');
        $this->assertSamePermission($formRequest, $createdPermission);
    }

    /**
     * Given permission-1 exists and permission-2 does not exists.
     * When request form permission-2 is made
     *      and permission-2 has same name as permission-1,
     * then creation of permission-2 should failed.
     * 
     * TODO: Exception is too general
     * @expectedException Exception
     */
    public function testCreateWithNonUniqueName()
    {
        $validFormRequest = $this->getMockRequest();
        $validPermission = $this->permissionRepository->create($validFormRequest);
        
        $nonUniqueNameFormRequest = $this->getMockRequest();
        $nonUniqueNameFormRequest['name'] = $validPermission->name;

        $this->permissionRepository->create($nonUniqueNameFormRequest);
    }

    /**
     * Given permission exists.
     * When request for an update is sent,
     * then permission is updated successfully.
     */
    public function testUpdateValidPermission()
    {
        $formRequest = $this->getMockRequest();
        $createPermission = $this->permissionRepository->create($formRequest);

        $updatedPermission = $this->permissionRepository->update($createPermission, $formRequest);
        
        $this->assertSamePermission($formRequest, $updatedPermission);
    }

    /**
     * Given permission is persisted.
     * When permission should be deleted,
     * then permission is destroyed without errors.
     */
    public function testDestroyValidPermission()
    {
        $formRequest = $this->getMockRequest();
        $createdPermission = $this->permissionRepository->create($formRequest);

        $this->permissionRepository->destroy($createdPermission);
        
        $actual = $this->permissionRepository->find($createdPermission->id);
        $this->assertTrue(null == $actual);
    }
    
    private function getMockRequest()
    {
        $formRequest = [
            'name' => $this->faker->unique()->userName,
            'description' => $this->faker->sentence
        ];

        return $formRequest;
    }
    
    private function assertSamePermission(array $expected, $actual)
    {
        $this->assertEquals($expected['name'], $actual->name, 'Name should be same');
        $this->assertEquals($expected['description'], $actual->description, 'Description should be same');
    }
    
    private function getRandomPermission($isPersisted = false)
    {
        $permission = new Permission;
        $permission->name = $this->faker->userName;
        $permission->description = $this->faker->sentence();
        $permission->id = $isPersisted ? $this->faker->unique()->randomNumber() : null;
        
        return $permission;
    }

}
