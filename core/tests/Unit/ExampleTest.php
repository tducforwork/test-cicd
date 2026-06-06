<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_ci_can_run_phpunit(): void
    {
        $this->assertSame(4, 2 + 2);
    }
}