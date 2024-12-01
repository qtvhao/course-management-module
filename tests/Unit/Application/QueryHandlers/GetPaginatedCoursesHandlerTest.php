<?php

namespace Qtvhao\CourseManagement\Tests\Application\QueryHandlers;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Application\DataQueries\GetPaginatedCoursesQuery;
use Qtvhao\CourseManagement\Application\DTOs\PaginatedCoursesDTO;
use Qtvhao\CourseManagement\Application\QueryHandlers\GetPaginatedCoursesHandler;
use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseReadRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Entities\Course;

class GetPaginatedCoursesHandlerTest extends TestCase
{
    public function testHandle_ShouldReturnPaginatedCoursesDTO(): void
    {
        // Arrange
        $mockRepository = $this->createMock(CourseReadRepositoryInterface::class);

        $mockCourses = [
            new Course(id: '1', title: 'Course A', duration: 60, prerequisites: []),
            new Course(id: '2', title: 'Course B', duration: 90, prerequisites: [])
        ];

        $mockPaginatedResult = [
            'data' => $mockCourses,
            'totalPages' => 10,
            'currentPage' => 1,
            'itemsPerPage' => 2
        ];

        $mockRepository
            ->expects($this->once())
            ->method('findPaginated')
            ->with(1, 2, 'title', 'asc')
            ->willReturn($mockPaginatedResult);

        $handler = new GetPaginatedCoursesHandler($mockRepository);
        $query = new GetPaginatedCoursesQuery(page: 1, perPage: 2, sortBy: 'title', sortOrder: 'asc');

        // Act
        $result = $handler->handle($query);

        // Assert
        $this->assertInstanceOf(PaginatedCoursesDTO::class, $result);
        $this->assertCount(2, $result->courses);
        $this->assertEquals(10, $result->totalPages);
        $this->assertEquals(1, $result->currentPage);
        $this->assertEquals(2, $result->itemsPerPage);

        $this->assertEquals('1', $result->courses[0]->id);
        $this->assertEquals('Course A', $result->courses[0]->title);
        $this->assertEquals(60, $result->courses[0]->duration);
    }
}
