<?php

namespace Qtvhao\CourseManagement\Domain\Contracts\Repositories;

use Qtvhao\CourseManagement\Domain\Entities\Course;

interface CourseWriteRepositoryInterface
{
    /**
     * Create a new course.
     *
     * @param Course $course
     * @return Course
     */
    public function create(Course $course): Course;

    /**
     * Update an existing course.
     *
     * @param Course $course
     * @return Course
     */
    public function update(Course $course): Course;

    /**
     * Delete a course by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
