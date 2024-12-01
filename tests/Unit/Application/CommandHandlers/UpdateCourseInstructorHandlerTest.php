<?php

namespace Qtvhao\CourseManagement\Tests\Unit\Application\CommandHandlers;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\CommandHandlers\UpdateCourseInstructorHandler;
use Qtvhao\CourseManagement\Application\Commands\UpdateCourseInstructorCommand;
use Qtvhao\CourseManagement\Domain\Aggregates\CourseAggregate;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseReadRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseWriteRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Events\CourseInstructorUpdatedEvent;
use Qtvhao\CourseManagement\Infrastructure\Messaging\EventBusInterface;

class UpdateCourseInstructorHandlerTest extends TestCase
{
    private $readRepository;
    private $writeRepository;
    private $eventBus;
    private $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->readRepository = $this->createMock(CourseReadRepositoryInterface::class);
        $this->writeRepository = $this->createMock(CourseWriteRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);

        $this->handler = new UpdateCourseInstructorHandler(
            $this->readRepository,
            $this->writeRepository,
            $this->eventBus
        );
    }

    public function testHandleUpdatesInstructorSuccessfully(): void
    {
        $courseId = 'course-123';
        $instructorId = 'instructor-456';

        // Create a mock CourseAggregate
        $course = $this->createMock(CourseAggregate::class);
        $course->method('getId')->willReturn($courseId);
        $course->expects($this->once())
            ->method('updateInstructor')
            ->with($instructorId);

        // Mock the read repository to return the course
        $this->readRepository->expects($this->once())
            ->method('findById')
            ->with($courseId)
            ->willReturn($course);

        // Mock the write repository to save the course
        $this->writeRepository->expects($this->once())
            ->method('save')
            ->with($course);

        // Mock the event bus to publish the event
        $this->eventBus->expects($this->once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($courseId, $instructorId) {
                return $event instanceof CourseInstructorUpdatedEvent &&
                    $event->getCourseId() === $courseId &&
                    $event->getInstructorId() === $instructorId;
            }));

        // Create and dispatch the command
        $command = new UpdateCourseInstructorCommand($courseId, $instructorId);
        $this->handler->handle($command);
    }

    public function testHandleThrowsExceptionIfCourseNotFound(): void
    {
        $courseId = 'course-123';
        $instructorId = 'instructor-456';

        // Mock the read repository to return null
        $this->readRepository->expects($this->once())
            ->method('findById')
            ->with($courseId)
            ->willReturn(null);

        // Expect an exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Course with ID $courseId not found.");

        // Create and dispatch the command
        $command = new UpdateCourseInstructorCommand($courseId, $instructorId);
        $this->handler->handle($command);
    }
}
