<?php

use Foxytouch\Tests\TestCase;
use Foxytouch\User\Models\Permission;
use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

/**
 * Class RoleCrudTestCase
 *
 * @package \Foxytouch\User\Tests\Unit
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class RoleCrudTestCase extends TestCase
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    
    public function setUp()
    {
        parent::setUp();

        $this->roleRepository = $this->app->make(RoleRepository::class);
        $this->permissionRepository = $this->app->make(PermissionRepository::class);
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Given role does not exists.
     * When request for creating is made 
     *      and role has not attached any permissions,
     * then role is created successfully.
     */
    public function testCreateValidRoleWithoutAttachedPermissions()
    {
        $formRequest = $this->getMockRequest();

        $createdRole = $this->roleRepository->create($formRequest);
        
        $this->assertTrue(null != $createdRole, 'Role should be persisted');
        $this->assertSameRole($formRequest, $createdRole);
    }
    
    /**
     * Given role does not exists.
     * When request for creation is made
     *      and role has attached one or more permissions,
     * then role is created successfully.
     */
    public function testCreateValidRoleWithAttachedPermissions()
    {
        $formRequest = $this->getMockRequestWithPermissions(2);
        $createdRole = $this->roleRepository->create($formRequest);
        
        $this->assertSameRole($formRequest, $createdRole);
    }

    /**
     * Given role does exists.
     * When request for update is made,
     *      such that one or more permissions are removed,
     * then permissions are detached from role.
     */
    public function testUpdateRole()
    {
        $this->markTestSkipped('Issue');
        $createdRole = $this->roleRepository->create($this->getMockRequest());

        // Update role
        $formRequest = $this->getMockRequest();
        $this->roleRepository->update($createdRole, $formRequest);
        $this->assertSameRole($formRequest, $createdRole);

        // Add permissions
        $formRequest = $this->getMockRequestWithPermissions(3);
        $this->roleRepository->update($createdRole, $formRequest);
        $this->assertSameRole($formRequest, $createdRole);

        // Remove one permission
        array_pop($formRequest['permission']);
        $this->roleRepository->update($createdRole, $formRequest);
        $this->assertSameRole($formRequest, $createdRole);
        
        // Remove all permissions
        $formRequest = $this->getMockRequest();
        $this->roleRepository->update($createdRole, $formRequest);
        $this->assertSameRole($formRequest, $createdRole);
    }

    /**
     * Given role exists.
     * When request for removing is made
     *      and role has not attached any permissions,
     * then role is removed.
     */
    public function testDestroyValidRoleWithoutPermission()
    {
        $createdRole = $this->roleRepository->create($this->getMockRequest());

        $this->roleRepository->destroy($createdRole);
        
        $actual = $this->roleRepository->find($createdRole->id);
        $this->assertTrue(null == $actual, 'Role should be destroyed');
    }

    /**
     * Given role exists.
     * When request for removing is made
     *      and role has attached one or more permissions,
     * then role is removed.
     */
    public function testDestroyValidRoleWithPermissions()
    {
        $createdRole = $this->roleRepository->create($this->getMockRequestWithPermissions(3));

        $this->roleRepository->destroy($createdRole);
        
        $actual = $this->roleRepository->find($createdRole->id);
        $this->assertTrue(null == $actual, 'Role should be destroyed');

        foreach ($createdRole->permissions as $permission) {
            $actual = $this->permissionRepository->find($permission->id);
            $this->assertFalse(null == $actual, 'Role\'s permission should not be destroyed');
        }
    }
    
    private function getMockRequest()
    {
        return [
            'name' => $this->faker->unique()->userName,
            'description' => $this->faker->sentence
        ];

    }
    
    private function getMockRequestWithPermissions($count)
    {
        $formRequest = $this->getMockRequest();

        if ($count) {
            $formRequest['permission'] = [];
            
            while ($count--) {
                $permission = $this->getRandomPermission(true);
                array_push($formRequest['permission'], $permission->id);
            }
        }

        return $formRequest;
    }
    
    private function assertSameRole(array $expected, $actual)
    {
        $this->assertEquals($expected['name'], $actual->name, 'Name should be same');
        $this->assertEquals($expected['description'], $actual->description, 'Description should be same');
        if (array_key_exists('permission', $expected) && is_array($expected['permission'])) {
            $actualPermissions = array_map('intval', $actual->permissions->pluck('id')->toArray());
            $this->assertEquals($expected['permission'], $actualPermissions);
        }
    }

    private function getRandomPermission($isPersisted = false)
    {
        $permission = new Permission;
        $permission->name = $this->faker->userName;
        $permission->description = $this->faker->sentence();
        
        if ($isPersisted) {
            $permission = $this->permissionRepository->create($permission->toArray());
        }

        return $permission;
    }
}
