Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```
CourseManagement/
├── composer.json
├── docs/
│   └── README.md
├── src/
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
│   │       └── CourseSummaryDTO.php
│   ├── Domain/
│   │   ├── Entities/
│   │   │   └── Course.php
│   │   ├── Repositories/
│   │   │   ├── CourseRepositoryInterface.php
│   │   │   └── CourseReadRepositoryInterface.php
│   │   ├── ValueObjects/
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   └── CourseDuration.php
│   │   └── Services/
│   │       └── CourseDomainService.php
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
│   │       └── CourseCreated.php
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
│   │       └── web.php
├── tests/
│   ├── Feature/
│   │   ├── CourseControllerTest.php
│   ├── Unit/
│   │   ├── Application/
│   │   │   ├── CommandHandlers/
│   │   │   └── QueryHandlers/
│   │   ├── Domain/
│   │   │   ├── Entities/
│   │   │   ├── Services/
│   │   │   └── Repositories/
│   └── Infrastructure/
│       └── Persistence/
```
