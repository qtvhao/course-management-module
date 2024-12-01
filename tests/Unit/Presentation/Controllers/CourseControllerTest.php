<?php

namespace Qtvhao\CourseManagement\Tests\Unit\Presentation\Controllers;

use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\Services\UpdateCourseInstructorValidator;
use Qtvhao\CourseManagement\Application.Commands\UpdateCourseInstructorCommand;
use Qtvhao\CourseManagement\Infrastructure\Messaging\QueueCommandBus;
use Qtvhao\CourseManagement\Presentation\Controllers\CourseController;

class CourseControllerTest extends TestCase
{
    private MockInterface $commandBusMock;
    private MockInterface $validatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange: Create mock dependencies
        $this->commandBusMock = Mockery::mock(QueueCommandBus::class);
        $this->validatorMock = Mockery::mock(UpdateCourseInstructorValidator::class);
    }

    public function testUpdateInstructorSuccess(): void
    {
        // Arrange
        $courseId = 'course-123';
        $instructorId = 'instructor-456';

        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('validate')
            ->once()
            ->with(['instructor_id' => 'required|uuid'])
            ->andReturn(['instructor_id' => $instructorId]);

        $this->validatorMock->shouldReceive('validate')
            ->once()
            ->with($courseId, $instructorId)
            ->andReturnNull();

        $this->commandBusMock->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function (UpdateCourseInstructorCommand $command) use ($courseId, $instructorId) {
                return $command->courseId === $courseId && $command->instructorId === $instructorId;
            }), 'high')
            ->andReturnNull();

        $controller = new CourseController($this->commandBusMock, $this->validatorMock);

        // Act
        /** @var JsonResponse $response */
        $response = $controller->updateInstructor($requestMock, $courseId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Instructor updated successfully'], $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
