<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Common setup logic for all tests, e.g., environment configuration
    }

    protected function tearDown(): void
    {
        // Common tear-down logic for all tests
        parent::tearDown();
    }

    /**
     * Utility for creating mock objects with type hints.
     */
    protected function createMockFor(string $class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
