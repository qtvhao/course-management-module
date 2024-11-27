<?php
namespace Tests\Unit\Domain\Aggregates;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Aggregates\CourseAggregate;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\ValueObjects\StudentId;

class CourseAggregateTest extends TestCase
{
    public function testRegisterStudentSuccessfully(): void
    {
        // Arrange
        $courseIdMock = $this->createMock(CourseId::class);
        $studentIdMock = $this->createMock(StudentId::class);

        $courseAggregate = new CourseAggregate($courseIdMock, 10);

        // Act
        $courseAggregate->registerStudent($studentIdMock);

        // Assert
        $this->assertFalse($courseAggregate->isFull());
    }

    public function testThrowsExceptionWhenRegisteringStudentToFullCourse(): void
    {
        // Arrange
        $courseIdMock = $this->createMock(CourseId::class);
        $studentIdMock = $this->createMock(StudentId::class);

        $courseAggregate = new CourseAggregate($courseIdMock, 1);

        // Register the first student
        $courseAggregate->registerStudent($studentIdMock);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Course is already full.');

        // Act
        $courseAggregate->registerStudent($studentIdMock);
    }

    public function testThrowsExceptionWhenStudentAlreadyRegistered(): void
    {
        // Arrange
        $courseIdMock = $this->createMock(CourseId::class);
        $studentIdMock = $this->createMock(StudentId::class);

        $courseAggregate = new CourseAggregate($courseIdMock, 10);

        // Register the student for the first time
        $courseAggregate->registerStudent($studentIdMock);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Student is already registered.');

        // Act
        $courseAggregate->registerStudent($studentIdMock);
    }
}
