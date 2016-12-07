<?php

use Foxytouch\Tests\TestCase;
use Foxytouch\User\Models\Role;
use Foxytouch\User\Models\User;
use Foxytouch\User\Models\Permission;
use Foxytouch\User\Repositories\Contracts\RoleRepository;
use Foxytouch\User\Repositories\Contracts\UserRepository;
use Foxytouch\User\Repositories\Contracts\PermissionRepository;

use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudTestCase
 *
 * @package Foxytouch\User\Tests\Unit
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UserCrudTestCase extends TestCase
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    
    public function setUp()
    {
        parent::setUp();

        $this->roleRepository = $this->app->make(RoleRepository::class);
        $this->userRepository = $this->app->make(UserRepository::class);
        $this->permissionRepository = $this->app->make(PermissionRepository::class);
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
    public function testCreateUserWithoutPermissionsOrRolesWithoutPicture()
    {
        $formRequest = $this->getMockFormRequest();
        $this->createAndAssert($formRequest);
    }

    /**
     * Given
     * When
     * then
     */
    public function testCreateUserWithoutPermissionsOrRolesWithPicture()
    {
        $formRequest = $this->getMockFormRequest(true);
        $this->createAndAssert($formRequest);
    }

    /**
     * Given
     * When
     * then
     */
    public function testCreateUserWithPermissionsWithoutRoles()
    {
        $formRequest = $this->getMockFormRequest(true, 0, 2);
        $this->createAndAssert($formRequest);
    }

    /**
     * Given
     * When
     * then
     */
    /* TODO: Use case when roles have also permissions */
    public function testCreateUserWithoutPermissionsWithRoles()
    {
        $formRequest = $this->getMockFormRequest(true, 4, 0);
        $this->createAndAssert($formRequest);
    }

    /**
     * Given
     * When
     * then
     */
    /* TODO: Use case when roles have also permissions */
    public function testCreateUserWithPermissionsWithRoles()
    {
        $formRequest = $this->getMockFormRequest(true, 4, 3);
        $this->createAndAssert($formRequest);
    }

    private function createAndAssert($formRequest)
    {
        $createdUser = $this->userRepository->create($formRequest);

        $this->assertTrue(null != $createdUser->id, 'User was not persisted');
        $this->assertSameUser($formRequest, $createdUser);
    }
    
    private function assertSameUser(array $expected, $actual)
    {
        $this->assertEquals($expected['username'], $actual->username, 'Username should be same.');
        $this->assertEquals($expected['email'], $actual->email, 'Email should be same.');
        $this->assertEquals($expected['ip'], $actual->ip, 'IP address should be same.');
        $this->assertEquals($expected['profile_picture'], $actual->profile_picture, 'Profile picture should be same.');
        $this->assertTrue(Hash::check($expected['password'], $actual->password), 'Password should be same.');

        /* TODO: Roles and perm checking */
    }

    private function getMockFormRequest($hasProfilePicture = false, $permissionsCount = 0, $rolesCount = 0)
    {
        $formRequest = [
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password(),
            'ip' => $this->faker->ipv4,
        ];

        if ($hasProfilePicture) {
            $picture = $this->faker->image(sys_get_temp_dir(), 300, 300);
            $formRequest['profile_picture'] = $picture;
        }

        return $formRequest;
    }

    private function getRandomUser($isPersisted = false, $hasProfilePicture = false)
    {
        $user = new User;
        $user->username = $this->faker->userName;
        $user->email = $this->faker->unique()->safeEmail;
        $user->password = bcrypt($this->faker->password());
        $user->ip = $this->faker->ipv4;
        $user->profile_picture = $hasProfilePicture ? $this->faker->image(sys_get_temp_dir(), 300, 300) : null;
        $user->id = $isPersisted ? $this->faker->unique()->randomNumber() : null;

        return $user;
    }

    private function getRandomRole($isPersisted = false)
    {
        $role = new Role;
        $role->name = $this->faker->userName;
        $role->description = $this->faker->sentence();
        
        if ($isPersisted) {
            $role = $this->permissionRepository->create($role->toArray());
        }
        
        return $role;
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

    private function getMockRequestWithRoles($count)
    {
        $formRequest = $this->getMockRequest();

        if ($count) {
            $formRequest['role'] = [];

            while ($count--) {
                $role = $this->getRandomRole(true);
                array_push($formRequest['role'], $role->id);
            }
        }

        return $formRequest;
    }

    public function getMockRequestWithPermissionsAndRoles($rolesCount, $permissionsCount)
    {
        $formRequest = $this->getMockRequest();
        
        if ($permissionsCount) {
            $formRequest['permission'] = [];

            while ($permissionsCount--) {
                $permission = $this->getRandomPermission(true);
                array_push($formRequest['permission'], $permission->id);
            }
        }

        if ($rolesCount) {
            $formRequest['role'] = [];

            while ($rolesCount--) {
                $role = $this->getRandomRole(true);
                array_push($formRequest['role'], $role->id);
            }
        }

        return $formRequest;
    }
}
