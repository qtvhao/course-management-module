<?php
namespace Qtvhao\CourseManagement\Tests\Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Entities\Course;
use Qtvhao\CourseManagement\Domain\Entities\Instructor;
use Qtvhao\CourseManagement\Domain\Exceptions\InstructorAvailabilityException;
use Qtvhao\CourseManagement\Domain\Services\CourseDomainService;

class CourseDomainServiceTest extends TestCase
{
    private CourseDomainService $courseDomainService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->courseDomainService = new CourseDomainService();
    }

    public function testInstructorHasSufficientAvailability(): void
    {
        // Arrange: Mock instructor and course
        $instructor = $this->createMock(Instructor::class);
        $course = $this->createMock(Course::class);

        // Giả sử giảng viên hiện đang có 20 giờ đã lên lịch
        $instructor->method('getTotalScheduledHours')->willReturn(20);
        $instructor->method('getMaxTeachingHoursPerWeek')->willReturn(40);

        // Giả sử khóa học cần 10 giờ và không bị trùng lịch
        $course->method('getDuration')->willReturn(10);
        $instructor->method('getScheduledCourses')->willReturn([]);
        
        // Act & Assert: Không có exception được ném ra
        $this->courseDomainService->validateInstructorAvailability($instructor, $course);
        $this->assertTrue(true, 'Instructor has sufficient availability.');
    }

    public function testInstructorExceedsMaxTeachingHours(): void
    {
        $this->expectException(InstructorAvailabilityException::class);

        // Arrange
        $instructor = $this->createMock(Instructor::class);
        $course = $this->createMock(Course::class);

        // Giả sử giảng viên hiện đã giảng dạy 35 giờ, giới hạn là 40 giờ
        $instructor->method('getTotalScheduledHours')->willReturn(35);
        $instructor->method('getMaxTeachingHoursPerWeek')->willReturn(40);

        // Khóa học này cần thêm 10 giờ, dẫn đến vượt quá giới hạn
        $course->method('getDuration')->willReturn(10);

        // Act: Gọi phương thức và mong đợi exception
        $this->courseDomainService->validateInstructorAvailability($instructor, $course);
    }

    public function testInstructorScheduleConflict(): void
    {
        $this->expectException(InstructorAvailabilityException::class);

        // Arrange
        $instructor = $this->createMock(Instructor::class);
        $course = $this->createMock(Course::class);

        // Mock các khóa học đã lên lịch của giảng viên
        $scheduledCourse = $this->createMock(Course::class);
        $instructor->method('getScheduledCourses')->willReturn([$scheduledCourse]);

        // Mock logic kiểm tra xung đột lịch
        $scheduledCourse->method('conflictsWith')->with($course)->willReturn(true);

        // Act: Gọi phương thức và mong đợi exception
        $this->courseDomainService->validateInstructorAvailability($instructor, $course);
    }

    public function testNoScheduleConflictAndSufficientAvailability(): void
    {
        // Arrange
        $instructor = $this->createMock(Instructor::class);
        $course = $this->createMock(Course::class);

        // Giả sử giảng viên hiện giảng dạy 20 giờ và có giới hạn là 40 giờ
        $instructor->method('getTotalScheduledHours')->willReturn(20);
        $instructor->method('getMaxTeachingHoursPerWeek')->willReturn(40);

        // Không có khóa học bị trùng lịch
        $scheduledCourse = $this->createMock(Course::class);
        $instructor->method('getScheduledCourses')->willReturn([$scheduledCourse]);
        $scheduledCourse->method('conflictsWith')->with($course)->willReturn(false);

        // Giả sử khóa học cần 10 giờ
        $course->method('getDuration')->willReturn(10);

        // Act & Assert: Không có exception
        $this->courseDomainService->validateInstructorAvailability($instructor, $course);
        $this->assertTrue(true, 'No conflict and sufficient availability.');
    }
}
