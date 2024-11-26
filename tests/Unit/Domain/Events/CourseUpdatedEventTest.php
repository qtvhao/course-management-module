<?php
namespace Tests\Unit\Domain\Events;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Events\CourseUpdatedEvent;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseTitle;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseDuration;
use Mockery;

class CourseUpdatedEventTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close(); // Đảm bảo đóng tất cả các mock sau mỗi test.
        parent::tearDown();
    }

    public function test_course_updated_event_is_triggered_correctly()
    {
        // Arrange: Tạo giá trị giả định cho sự kiện.
        $courseId = new CourseId(1);
        $oldTitle = new CourseTitle('Old Title');
        $newTitle = new CourseTitle('New Title');
        $oldDuration = new CourseDuration(90);
        $newDuration = new CourseDuration(120);

        $oldCourse = new Course($courseId, $oldTitle, $oldDuration);
        $newCourse = new Course($courseId, $newTitle, $newDuration);

        // Act: Khởi tạo sự kiện.
        $event = new CourseUpdatedEvent($oldCourse, $newCourse);
        // Assert: Kiểm tra các thuộc tính của sự kiện.
        $this->assertInstanceOf(CourseUpdatedEvent::class, $event);
        $this->assertEquals($courseId, $event->courseId());
        $this->assertEquals($oldTitle, $event->oldTitle());
        $this->assertEquals($newTitle, $event->newTitle());
        $this->assertEquals($oldDuration, $event->oldDuration());
        $this->assertEquals($newDuration, $event->newDuration());
    }

    public function test_event_dispatcher_handles_course_updated_event()
    {
        // Arrange: Mock Event Dispatcher.
        $eventDispatcherMock = Mockery::mock('Qtvhao\CourseManagement\Application\EventDispatcher');
        $eventDispatcherMock->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(CourseUpdatedEvent::class));

        // Arrange: Tạo sự kiện.
        $courseId = new CourseId(1);
        $oldTitle = new CourseTitle('Old Title');
        $newTitle = new CourseTitle('New Title');
        $oldDuration = new CourseDuration(90);
        $newDuration = new CourseDuration(120);

        $oldCourse = new Course($courseId, $oldTitle, $oldDuration);
        $newCourse = new Course($courseId, $newTitle, $newDuration);

        $event = new CourseUpdatedEvent($oldCourse, $newCourse);

        // Act: Dispatch sự kiện.
        $eventDispatcherMock->dispatch($event);

        // Assert: Mọi thứ diễn ra như mong đợi (Mockery sẽ tự kiểm tra).
    }
}
