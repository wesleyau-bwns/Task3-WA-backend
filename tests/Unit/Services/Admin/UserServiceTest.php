<?php

namespace Tests\Unit\Services\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Services\Admin\UserService;

use Mockery;

class UserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testListUsersReturnsActiveUsers()
    {
        $mockedUsers = collect([
            (object)['name' => 'John'],
            (object)['name' => 'Jane'],
        ]);

        // Mock the query builder
        $queryMock = Mockery::mock();
        $queryMock->shouldReceive('get')->once()->andReturn($mockedUsers);

        $userModelMock = Mockery::mock('alias:' . User::class);
        $userModelMock->shouldReceive('where')
            ->with('account_status', 'active')
            ->andReturn($queryMock);

        $service = new UserService();
        $result = $service->listUsers();

        $this->assertCount(2, $result);
        $this->assertEquals('John', $result[0]->name);
    }
}
