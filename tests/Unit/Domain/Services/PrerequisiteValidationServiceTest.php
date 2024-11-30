<?php

namespace Tests\Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Services\PrerequisiteValidationService;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\Exceptions\PrerequisiteValidationException;

class PrerequisiteValidationServiceTest extends TestCase
{
    private PrerequisiteValidationService $service;

    protected function setUp(): void
    {
        $this->service = new PrerequisiteValidationService();
    }

    public function testValidateReturnsTrueForValidPrerequisites()
    {
        // Arrange
        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-2'), new CourseId('course-3')];

        // Act
        $result = $this->service->validate($courseId, $prerequisiteIds);

        // Assert
        $this->assertTrue($result, 'Validation should return true for valid prerequisites.');
    }

    public function testValidateThrowsExceptionForCircularDependency()
    {
        // Arrange
        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-1')]; // Circular dependency

        // Assert
        $this->expectException(PrerequisiteValidationException::class);

        // Act
        $this->service->validate($courseId, $prerequisiteIds);
    }
}
