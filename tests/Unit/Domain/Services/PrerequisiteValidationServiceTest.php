<?php

namespace Tests\Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Services\PrerequisiteValidationService;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseReadRepositoryInterface;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;

class PrerequisiteValidationServiceTest extends TestCase
{
    private PrerequisiteValidationService $service;
    private $mockRepository;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(CourseReadRepositoryInterface::class);
        $this->service = new PrerequisiteValidationService($this->mockRepository);
    }

    public function testValidatePrerequisitesReturnsTrueForValidPrerequisites()
    {
        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-2'), new CourseId('course-3')];

        $this->mockRepository->method('existsById')->willReturn(true);
        $this->mockRepository->method('getPrerequisites')->willReturn([]);

        $result = $this->service->validatePrerequisites($courseId, $prerequisiteIds);

        $this->assertTrue($result);
    }

    public function testValidatePrerequisitesThrowsExceptionForNonExistentCourse()
    {
        $this->expectException(\InvalidArgumentException::class);

        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('non-existent-course')];

        $this->mockRepository->method('existsById')->willReturn(false);

        $this->service->validatePrerequisites($courseId, $prerequisiteIds);
    }

    public function testValidatePrerequisitesThrowsExceptionForCircularDependency()
    {
        $this->expectException(\InvalidArgumentException::class);

        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-2')];

        $this->mockRepository->method('existsById')->willReturn(true);
        $this->mockRepository->method('getPrerequisites')->willReturn([$courseId]);

        $this->service->validatePrerequisites($courseId, $prerequisiteIds);
    }
}
