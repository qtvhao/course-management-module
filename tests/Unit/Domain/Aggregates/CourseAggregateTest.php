<?php
namespace Qtvhao\CourseManagement\Tests\Unit\Domain\Aggregates;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Aggregates\CourseAggregate;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseTitle;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseDuration;
use Qtvhao\CourseManagement\Domain\Events\CourseCreatedEvent;

class CourseAggregateTest extends TestCase
{
    public function testCourseCreatedEventIsDispatched()
    {
        // Arrange: Khởi tạo giá trị cho Course
        $courseId = new CourseId('course-123');
        $courseTitle = new CourseTitle('Introduction to Clean Architecture');
        $courseDuration = new CourseDuration(10); // 10 hours

        // Act: Tạo CourseAggregate
        $courseAggregate = CourseAggregate::create($courseId, $courseTitle, $courseDuration);

        // Assert: Lấy các sự kiện được tạo từ Aggregate
        $events = $courseAggregate->releaseEvents();

        // Kiểm tra rằng chỉ có 1 sự kiện
        $this->assertCount(1, $events);

        // Kiểm tra sự kiện là CourseCreatedEvent
        $this->assertInstanceOf(CourseCreatedEvent::class, $events[0]);

        // Kiểm tra các thuộc tính của CourseCreatedEvent
        $event = $events[0];
        $this->assertEquals($courseId, $event->getCourseId());
        $this->assertEquals($courseTitle, $event->getCourseTitle());
        $this->assertEquals($courseDuration, $event->getCourseDuration());
    }
}
