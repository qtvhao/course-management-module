<?php

namespace Qtvhao\CourseManagement\Application\Commands;

use Qtvhao\SharedModule\Contracts\BaseCommandInterface;

/**
 * Command to encapsulate the data required to create a new course.
 */
class CreateCourseCommand implements BaseCommandInterface
{
    /**
     * @var string
     */
    private string $courseId;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string|null
     */
    private ?string $description;

    /**
     * @var int
     */
    private int $duration;

    /**
     * Constructor.
     *
     * @param string $courseId Unique identifier for the course.
     * @param string $title Title of the course.
     * @param string|null $description Optional description of the course.
     * @param int $duration Duration of the course in minutes.
     */
    public function __construct(
        string $courseId,
        string $title,
        ?string $description,
        int $duration
    ) {
        $this->courseId = $courseId;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
    }

    /**
     * Get the course ID.
     *
     * @return string
     */
    public function getCourseId(): string
    {
        return $this->courseId;
    }

    /**
     * Get the course title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the course description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the course duration in minutes.
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}
