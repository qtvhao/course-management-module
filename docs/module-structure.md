Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```
course-management-module
├── composer.json
├── docs/
│   └── README.md
├── src/
│   ├── Application/
│   │   ├── Commands/
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   ├── DeleteCourseCommand.php
│   │   │   ├── EnrollStudentCommand.php
│   │   │   └── ApproveEnrollmentCommand.php
│   │   ├── CommandHandlers/
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   ├── DeleteCourseHandler.php
│   │   │   ├── EnrollStudentHandler.php
│   │   │   └── ApproveEnrollmentHandler.php
│   │   ├── Queries/
│   │   │   ├── GetCourseByIdQuery.php
│   │   │   ├── GetAllCoursesQuery.php
│   │   │   ├── SearchCoursesQuery.php
│   │   │   ├── GetInstructorByIdQuery.php
│   │   │   ├── GetStudentByIdQuery.php
│   │   │   └── GetEnrollmentsByCourseQuery.php
│   │   ├── QueryHandlers/
│   │   │   ├── GetCourseByIdHandler.php
│   │   │   ├── GetAllCoursesHandler.php
│   │   │   ├── SearchCoursesHandler.php
│   │   │   ├── GetInstructorByIdHandler.php
│   │   │   ├── GetStudentByIdHandler.php
│   │   │   └── GetEnrollmentsByCourseHandler.php
│   │   ├── DTOs/
│   │   │   ├── CourseDTO.php
│   │   │   ├── CourseSummaryDTO.php
│   │   │   ├── InstructorDTO.php
│   │   │   ├── StudentDTO.php
│   │   │   └── EnrollmentDTO.php
│   │   └── Events/
│   │       ├── CourseCreatedEvent.php
│   │       ├── StudentEnrolledEvent.php
│   │       └── EnrollmentApprovedEvent.php
│   ├── Domain/
│   │   ├── Entities/
│   │   │   ├── Course.php
│   │   │   ├── Instructor.php
│   │   │   ├── Student.php
│   │   │   └── Enrollment.php
│   │   ├── Repositories/
│   │   │   ├── CourseRepositoryInterface.php
│   │   │   ├── EnrollmentRepositoryInterface.php
│   │   │   ├── InstructorRepositoryInterface.php
│   │   │   └── StudentRepositoryInterface.php
│   │   ├── ValueObjects/
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   ├── CourseDuration.php
│   │   │   ├── InstructorId.php
│   │   │   ├── StudentId.php
│   │   │   ├── EnrollmentId.php
│   │   │   ├── Email.php
│   │   │   └── EnrollmentStatus.php
│   │   ├── Services/
│   │   │   ├── CourseDomainService.php
│   │   │   ├── EnrollmentPolicy.php
│   │   │   └── EnrollmentDomainService.php
│   │   └── Events/
│   │       ├── CourseCreatedEvent.php
│   │       ├── StudentEnrolledEvent.php
│   │       └── EnrollmentApprovedEvent.php
│   ├── Infrastructure/
│   │   ├── Persistence/
│   │   │   ├── Eloquent/
│   │   │   │   ├── EloquentCourseRepository.php
│   │   │   │   ├── EloquentEnrollmentRepository.php
│   │   │   │   ├── EloquentInstructorRepository.php
│   │   │   │   └── EloquentStudentRepository.php
│   │   │   └── Migrations/
│   │   │       ├── 2024_01_01_000000_create_courses_table.php
│   │   │       ├── 2024_01_01_000001_create_instructors_table.php
│   │   │       ├── 2024_01_01_000002_create_students_table.php
│   │   │       └── 2024_01_01_000003_create_enrollments_table.php
│   │   ├── Providers/
│   │   │   └── CourseManagementServiceProvider.php
│   │   ├── Queries/
│   │   │   └── CourseQueryBuilder.php
│   │   └── Events/
│   │       └── StudentEnrolledListener.php
│   ├── Presentation/
│   │   ├── Controllers/
│   │   │   ├── CourseController.php
│   │   │   ├── EnrollmentController.php
│   │   │   ├── InstructorController.php
│   │   │   └── StudentController.php
│   │   ├── Requests/
│   │   │   ├── CreateCourseRequest.php
│   │   │   ├── UpdateCourseRequest.php
│   │   │   ├── EnrollStudentRequest.php
│   │   │   ├── ApproveEnrollmentRequest.php
│   │   │   └── DeleteCourseRequest.php
│   │   ├── Resources/
│   │   │   ├── views/
│   │   │   │   ├── courses/
│   │   │   │   │   ├── index.blade.php
│   │   │   │   │   ├── create.blade.php
│   │   │   │   │   └── edit.blade.php
│   │   │   │   ├── enrollments/
│   │   │   │   │   ├── list.blade.php
│   │   │   │   │   └── details.blade.php
│   │   │   │   └── instructors/
│   │   │   │       ├── index.blade.php
│   │   │   │       └── details.blade.php
│   │   │   └── lang/
│   │   │       └── en/
│   │   │           └── courses.php
│   │   └── Routes/
│   │       ├── courses.php
│   │       ├── enrollments.php
│   │       ├── instructors.php
│   │       └── students.php
├── tests/
│   ├── Feature/
│   │   ├── CourseControllerTest.php
│   │   ├── EnrollmentControllerTest.php
│   │   ├── InstructorControllerTest.php
│   │   └── StudentControllerTest.php
│   ├── Unit/
│   │   ├── Application/
│   │   │   ├── CommandHandlers/
│   │   │   │   ├── EnrollStudentHandlerTest.php
│   │   │   │   └── ApproveEnrollmentHandlerTest.php
│   │   │   ├── QueryHandlers/
│   │   │   └── EventTests/
│   │   ├── Domain/
│   │   │   ├── Entities/
│   │   │   │   ├── CourseTest.php
│   │   │   │   ├── InstructorTest.php
│   │   │   │   ├── StudentTest.php
│   │   │   │   └── EnrollmentTest.php
│   │   │   ├── Services/
│   │   │   │   ├── EnrollmentPolicyTest.php
│   │   │   │   └── CourseDomainServiceTest.php
│   │   │   └── Repositories/
│   │   └── Infrastructure/
│   │       └── Persistence/
│   │           └── Eloquent/
│   │               └── EloquentEnrollmentRepositoryTest.php
assessment-management-module
└── composer.json
learning-system-module
└── composer.json

```
