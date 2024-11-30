<?php
namespace Qtvhao\CourseManagement\Tests\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\Commands\CreateCourseCommand;
use Qtvhao\CourseManagement\Application\UseCases\CreateCourseUseCase;
use Qtvhao\CourseManagement\Domain\Aggregates\CourseAggregate;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseWriteRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Services\PrerequisiteValidationService;
use Qtvhao\CourseManagement\Domain\Events\CourseCreatedEvent;
use Qtvhao\CourseManagement\Infrastructure\Messaging\EventBusInterface;

class CreateCourseUseCaseTest extends TestCase
{
    public function testExecute_ShouldValidatePrerequisites_SaveCourseAndPublishEvent(): void
    {
        // Arrange (Setup mocks and input)
        $mockRepository = $this->createMock(CourseWriteRepositoryInterface::class);
        $mockValidator = $this->createMock(PrerequisiteValidationService::class);
        $mockEventBus = $this->createMock(EventBusInterface::class);

        $command = new CreateCourseCommand(
            title: 'Test Course',
            duration: 90,
            prerequisites: [1, 2, 3]
        );

        // Mock PrerequisiteValidationService behavior
        $mockValidator
            ->expects($this->once())
            ->method('validate')
            ->with($command->prerequisites);

        // Mock CourseWriteRepository behavior
        $mockRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (CourseAggregate $course) use ($command) {
                // Assert CourseAggregate has expected properties
                return $course->getTitle() === $command->title &&
                       $course->getDuration() === $command->duration;
            }));

        // Mock EventBus behavior
        $mockEventBus
            ->expects($this->exactly(1)) // Ensure one event is published
            ->method('publish')
            ->with($this->callback(function ($event) use ($command) {
                // Assert correct event is published
                return $event instanceof CourseCreatedEvent &&
                       $event->courseTitle === $command->title;
            }));

        // Create the use case with mocked dependencies
        $useCase = new CreateCourseUseCase(
            $mockRepository,
            $mockValidator,
            $mockEventBus
        );

        // Act
        $useCase->execute($command);

        // Assert (Implicit via expectations in mocks)
        // All mock expectations have been defined above.
    }
}
