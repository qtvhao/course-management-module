<?php

namespace Qtvhao\CourseManagement\Tests\Application\QueryHandlers;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\DataQueries\GetCourseByIdQuery;
use Qtvhao\CourseManagement\Application\DTOs\CourseDTO;
use Qtvhao\CourseManagement\Application\QueryHandlers\GetCourseByIdHandler;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseReadRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Entities\Course;
use InvalidArgumentException;

class GetCourseByIdHandlerTest extends TestCase
{
    public function testHandle_ShouldReturnCourseDTO_WhenCourseExists(): void
    {
        // Arrange
        $mockRepository = $this->createMock(CourseReadRepositoryInterface::class);

        $mockCourse = new Course(
            id: '12345',
            title: 'Test Course',
            duration: 90,
            prerequisites: [1, 2, 3]
        );

        $mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with('12345')
            ->willReturn($mockCourse);

        $handler = new GetCourseByIdHandler($mockRepository);
        $query = new GetCourseByIdQuery(courseId: '12345');

        // Act
        $result = $handler->handle($query);

        // Assert
        $this->assertInstanceOf(CourseDTO::class, $result);
        $this->assertEquals('12345', $result->id);
        $this->assertEquals('Test Course', $result->title);
        $this->assertEquals(90, $result->duration);
    }

    public function testHandle_ShouldThrowException_WhenCourseDoesNotExist(): void
    {
        // Arrange
        $mockRepository = $this->createMock(CourseReadRepositoryInterface::class);

        $mockRepository
            ->expects($this->once())
            ->method('findById')
            ->with('12345')
            ->willReturn(null);

        $handler = new GetCourseByIdHandler($mockRepository);
        $query = new GetCourseByIdQuery(courseId: '12345');

        // Assert Exception
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Course with ID 12345 not found.');

        // Act
        $handler->handle($query);
    }
}
