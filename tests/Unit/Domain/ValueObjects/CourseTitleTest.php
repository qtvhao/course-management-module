<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseTitle;

class CourseTitleTest extends TestCase
{
    public function test_can_create_course_title(): void
    {
        // Arrange
        $validTitle = "Introduction to Programming";

        // Act
        $courseTitle = new CourseTitle($validTitle);

        // Assert
        $this->assertInstanceOf(CourseTitle::class, $courseTitle);
        $this->assertEquals($validTitle, $courseTitle->value());
        $this->assertEquals($validTitle, (string) $courseTitle);
    }
}
