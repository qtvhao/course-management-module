Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```
course-management-module/
├── composer.json
├── docs/
│   └── README.md
├── src/
│   ├── Domain/
│   │   ├── Entities/
│   │   │   └── Course.php
│   │   ├── Repositories/
│   │   │   ├── CourseRepositoryInterface.php
│   │   │   └── CourseReadRepositoryInterface.php
│   │   ├── Events/
│   │   │   ├── CourseCreatedEvent.php
│   │   │   ├── CourseUpdatedEvent.php
│   │   │   └── CourseDeletedEvent.php
│   │   ├── ValueObjects/
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   └── CourseDuration.php
│   │   ├── Services/
│   │   │   └── CourseDomainService.php
│   │   └── AggregateRoots/
│   │       └── CourseAggregate.php
│   ├── Application/
│   │   ├── Commands/
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   └── DeleteCourseCommand.php
│   │   ├── CommandHandlers/
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   └── DeleteCourseHandler.php
│   │   ├── Queries/
│   │   │   ├── GetCourseByIdQuery.php
│   │   │   ├── GetAllCoursesQuery.php
│   │   │   └── SearchCoursesQuery.php
│   │   ├── QueryHandlers/
│   │   │   ├── GetCourseByIdHandler.php
│   │   │   ├── GetAllCoursesHandler.php
│   │   │   └── SearchCoursesHandler.php
│   │   └── DTOs/
│   │       ├── CourseDTO.php
│   │       ├── CourseSummaryDTO.php
│   │       └── CourseReadModel.php
│   ├── Infrastructure/
│   │   ├── Persistence/
│   │   │   ├── Eloquent/
│   │   │   │   ├── EloquentCourseRepository.php
│   │   │   │   └── EloquentCourseReadRepository.php
│   │   │   └── Migrations/
│   │   │       └── 2024_01_01_000000_create_courses_table.php
│   │   ├── Providers/
│   │   │   └── CourseManagementServiceProvider.php
│   │   ├── Queries/
│   │   │   └── CourseQueryBuilder.php
│   │   └── Events/
│   │       └── EventDispatcher.php
│   ├── Presentation/
│   │   ├── Controllers/
│   │   │   └── CourseController.php
│   │   ├── Requests/
│   │   │   ├── CreateCourseRequest.php
│   │   │   ├── UpdateCourseRequest.php
│   │   │   └── DeleteCourseRequest.php
│   │   ├── Resources/
│   │   │   ├── views/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   └── lang/
│   │   │       └── en/
│   │   │           └── courses.php
│   │   └── Routes/
│   │       ├── web.php
│   │       └── api.php
├── tests/
│   ├── Feature/
│   │   ├── CourseControllerTest.php
│   ├── Unit/
│   │   ├── Domain/
│   │   │   ├── Entities/
│   │   │   ├── ValueObjects/
│   │   │   └── Services/
│   │   ├── Application/
│   │   │   ├── CommandHandlers/
│   │   │   ├── QueryHandlers/
│   │   │   └── DTOs/
│   │   ├── Infrastructure/
│   │   │   ├── Persistence/
│   │   │   ├── Queries/
│   │   │   └── Events/
│   └── Presentation/
│       ├── Controllers/
│       └── Requests/
assessment-management-module
└── composer.json
learning-system-module
└── composer.json

```
