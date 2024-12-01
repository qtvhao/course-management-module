<?php
namespace Tests\Application\EventHandlers;

use Application\EventHandlers\SendEmailOnCourseCreated;
use Domain\Events\CourseCreatedEvent;
use Infrastructure\Services\Contracts\EmailServiceInterface;
use PHPUnit\Framework\TestCase;

class SendEmailOnCourseCreatedTest extends TestCase
{
    public function testHandleSendsEmail(): void
    {
        $emailServiceMock = $this->createMock(EmailServiceInterface::class);
        $emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(
                'instructor@example.com',
                'New Course Created: Test Course',
                "A new course titled 'Test Course' has been created. Please contact the instructor for details."
            );

        $handler = new SendEmailOnCourseCreated($emailServiceMock);

        $event = new CourseCreatedEvent('123', 'Test Course', 'instructor@example.com');
        $handler->handle($event);
    }
}
