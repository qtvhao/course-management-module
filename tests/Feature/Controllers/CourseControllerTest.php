<?php

namespace Qtvhao\CourseManagement\Tests\Feature\Controllers;

use Mockery;
use Tests\TestCase;
use Qtvhao\CourseManagement\Presentation\Controllers\CourseController;
use Qtvhao\CourseManagement\Application\Commands\UpdateCourseInstructorCommand;
use Qtvhao\CourseManagement\Infrastructure\Messaging\QueueCommandBus;
use Symfony\Component\HttpFoundation\Response;

class CourseControllerTest extends TestCase
{
    /**
     * Test updating instructor for a course.
     *
     * @return void
     */
    public function test_update_instructor_successfully(): void
    {
        // ARRANGE
        $mockCommandBus = Mockery::mock(QueueCommandBus::class);

        // Mock the command bus dispatch to ensure it's called correctly
        $mockCommandBus
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function (UpdateCourseInstructorCommand $command) {
                return $command->courseId === 1 && $command->instructorId === 2;
            }), QueueCommandBus::PRIORITY_NORMAL);

        $controller = new CourseController($mockCommandBus);

        $request = request()->merge([
            'instructor_id' => 2,
        ]);

        // ACT
        $response = $controller->updateInstructor($request, 1);

        // ASSERT
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([
            'message' => 'Instructor updated successfully',
            'course_id' => 1,
            'instructor_id' => 2,
        ]);
    }

    /**
     * Test validation failure when no instructor_id is provided.
     *
     * @return void
     */
    public function test_update_instructor_validation_fails(): void
    {
        // ARRANGE
        $mockCommandBus = Mockery::mock(QueueCommandBus::class);
        $controller = new CourseController($mockCommandBus);

        $request = request()->merge([]);

        // ACT
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // Assert is implicit in the exception
        $controller->updateInstructor($request, 1);
    }

    /**
     * Clean up mockery after each test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
