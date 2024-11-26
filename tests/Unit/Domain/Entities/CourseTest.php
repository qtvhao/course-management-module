<?php
namespace Qtvhao\CourseManagement\Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Entities\Course;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseTitle;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseDuration;

class CourseTest extends TestCase
{
    public function test_it_creates_a_course_entity()
    {
        // Arrange
        $id = new CourseId('123');
        $title = new CourseTitle('Introduction to PHP');
        $duration = new CourseDuration(120);

        // Act
        $course = new Course($id, $title, $duration);

        // Assert
        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals('123', $course->getId()->toString());
        $this->assertEquals('Introduction to PHP', $course->getTitle()->toString());
        $this->assertEquals(120, $course->getDuration()->toInt());
    }

    public function test_it_can_update_course_title()
    {
        // Arrange
        $id = new CourseId('123');
        $title = new CourseTitle('Old Title');
        $duration = new CourseDuration(120);
        $course = new Course($id, $title, $duration);

        $newTitle = new CourseTitle('Updated Title');

        // Act
        $course->updateTitle($newTitle);

        // Assert
        $this->assertEquals('Updated Title', $course->getTitle()->toString());
    }

    public function test_it_preserves_value_objects()
    {
        // Arrange
        $id = new CourseId('123');
        $title = new CourseTitle('Value Object Test');
        $duration = new CourseDuration(200);

        // Act
        $course = new Course($id, $title, $duration);

        // Assert
        $this->assertInstanceOf(CourseId::class, $course->getId());
        $this->assertInstanceOf(CourseTitle::class, $course->getTitle());
        $this->assertInstanceOf(CourseDuration::class, $course->getDuration());
    }
}
