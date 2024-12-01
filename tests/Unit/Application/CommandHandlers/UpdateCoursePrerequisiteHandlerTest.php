<?php
namespace Qtvhao\CourseManagement\Tests\Unit\Application\CommandHandlers;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\CommandHandlers\UpdateCoursePrerequisiteHandler;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseWriteRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Contracts\EventBusInterface;
use Qtvhao\CourseManagement\Application\Commands\UpdatePrerequisitesCommand;
use Qtvhao\CourseManagement\Domain\Entities\Course;
use Qtvhao\CourseManagement\Domain\Events\PrerequisitesUpdatedEvent;
use Qtvhao\CourseManagement\Domain\Exceptions\NotFoundException;

class UpdateCoursePrerequisiteHandlerTest extends TestCase
{
    private $repositoryMock;
    private $eventBusMock;
    private $handler;

    protected function setUp(): void
    {
        // Mock repository and event bus
        $this->repositoryMock = $this->createMock(CourseWriteRepositoryInterface::class);
        $this->eventBusMock = $this->createMock(EventBusInterface::class);

        // Create the handler
        $this->handler = new UpdateCoursePrerequisiteHandler(
            $this->repositoryMock,
            $this->eventBusMock
        );
    }

    public function testHandleSuccessfullyUpdatesPrerequisites(): void
    {
        // Arrange
        $courseId = 'course-123';
        $prerequisiteIds = ['prereq-1', 'prereq-2'];
        $command = new UpdatePrerequisitesCommand($courseId, $prerequisiteIds);

        $courseMock = $this->createMock(Course::class);

        // Mock repository behavior
        $this->repositoryMock->expects($this->once())
            ->method('findById')
            ->with($courseId)
            ->willReturn($courseMock);

        $courseMock->expects($this->once())
            ->method('updatePrerequisites')
            ->with($prerequisiteIds);

        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($courseMock);

        $domainEvent = $this->createMock(PrerequisitesUpdatedEvent::class);

        $courseMock->expects($this->once())
            ->method('releaseEvents')
            ->willReturn([$domainEvent]);

        $this->eventBusMock->expects($this->once())
            ->method('publish')
            ->with($domainEvent);

        // Act
        $this->handler->handle($command);

        // Assert
        // No exception thrown means success
        $this->assertTrue(true);
    }

    public function testHandleThrowsNotFoundExceptionIfCourseDoesNotExist(): void
    {
        // Arrange
        $courseId = 'invalid-course';
        $command = new UpdatePrerequisitesCommand($courseId, []);

        $this->repositoryMock->expects($this->once())
            ->method('findById')
            ->with($courseId)
            ->willReturn(null);

        // Assert exception
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Course not found.");

        // Act
        $this->handler->handle($command);
    }
}
