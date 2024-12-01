<?php

namespace Qtvhao\CourseManagement\Tests\Feature\Controllers;

use Mockery;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Qtvhao\CourseManagement\Presentation\Controllers\CourseController;
use Qtvhao\CourseManagement\Presentation\Requests\UpdateCourseInstructorRequest;
use Qtvhao\CourseManagement\Application\Commands\UpdateCourseInstructorCommand;
use Qtvhao\CourseManagement\Infrastructure\Messaging\QueueCommandBus;
use Symfony\Component\HttpFoundation\Response;

class CourseControllerTest extends TestCase
{
    public function test_update_instructor_successfully(): void
    {
        // ARRANGE
        $mockCommandBus = Mockery::mock(QueueCommandBus::class);
        $mockCommandBus
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function (UpdateCourseInstructorCommand $command) {
                return $command->courseId === 1 && $command->instructorId === 2;
            }), QueueCommandBus::PRIORITY_NORMAL);

        $mockRequest = Mockery::mock(UpdateCourseInstructorRequest::class);
        $mockRequest
            ->shouldReceive('validated')
            ->once()
            ->andReturn(['instructor_id' => 2]);

        $controller = new CourseController($mockCommandBus);

        // ACT
        $response = $controller->updateInstructor($mockRequest, 1);

        // ASSERT
        $this->assertInstanceOf(JsonResponse::class, $response);
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([
            'message' => 'Instructor updated successfully',
            'course_id' => 1,
            'instructor_id' => 2,
        ]);
    }
}
