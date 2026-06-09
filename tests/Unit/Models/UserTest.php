<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    // ─── isAdmin() ────────────────────────────────────────────────────────────

    /** @test */
    public function admin_role_is_recognized_as_admin(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function editor_role_is_recognized_as_admin(): void
    {
        $user = new User(['role' => 'editor']);
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function viewer_role_is_recognized_as_admin(): void
    {
        $user = new User(['role' => 'viewer']);
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function non_admin_role_is_not_recognized_as_admin(): void
    {
        $user = new User(['role' => 'guest']);
        $this->assertFalse($user->isAdmin());
    }

    // ─── isSuperAdmin() ───────────────────────────────────────────────────────

    /** @test */
    public function only_admin_role_is_super_admin(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->isSuperAdmin());
        $this->assertFalse((new User(['role' => 'editor']))->isSuperAdmin());
        $this->assertFalse((new User(['role' => 'viewer']))->isSuperAdmin());
    }

    // ─── isEditor() ───────────────────────────────────────────────────────────

    /** @test */
    public function admin_and_editor_roles_can_edit(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->isEditor());
        $this->assertTrue((new User(['role' => 'editor']))->isEditor());
        $this->assertFalse((new User(['role' => 'viewer']))->isEditor());
    }

    // ─── hasRole() ────────────────────────────────────────────────────────────

    /** @test */
    public function has_role_matches_exact_role(): void
    {
        $user = new User(['role' => 'editor']);
        $this->assertTrue($user->hasRole('editor'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('viewer'));
    }
}
