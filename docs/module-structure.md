Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```
course-management-module/
├── composer.json              // Dependency management configuration
├── docs/                      // Documentation about the module
│   └── README.md
├── src/
│   ├── Domain/                // Core domain logic and rules
│   │   ├── Entities/          // Core domain objects
│   │   │   └── Course.php
│   │   ├── Repositories/      // Abstractions for persistence (moved to Infrastructure/Contracts)
│   │   ├── ValueObjects/      // Immutable objects for properties
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   └── CourseDuration.php
│   │   ├── Events/            // Domain events for system interactions
│   │   │   ├── CourseCreatedEvent.php
│   │   │   ├── CourseUpdatedEvent.php
│   │   │   └── CourseDeletedEvent.php
│   │   ├── Services/          // Domain-specific services
│   │   │   └── CourseDomainService.php
│   │   └── AggregateRoots/    // Aggregate roots for domain consistency
│   │       └── CourseAggregate.php
│   ├── Application/           // Application logic (use cases, commands, DTOs)
│   │   ├── UseCases/          // Specific use cases
│   │   │   ├── CreateCourseUseCase.php
│   │   │   ├── UpdateCourseUseCase.php
│   │   │   └── DeleteCourseUseCase.php
│   │   ├── Commands/          // Data structures for write actions
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   └── DeleteCourseCommand.php
│   │   ├── CommandHandlers/   // Handlers to process commands
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   └── DeleteCourseHandler.php
│   │   ├── Queries/           // Data structures for read actions
│   │   │   ├── GetCourseByIdQuery.php
│   │   │   ├── GetAllCoursesQuery.php
│   │   │   └── SearchCoursesQuery.php
│   │   ├── QueryHandlers/     // Handlers to process queries
│   │   │   ├── GetCourseByIdHandler.php
│   │   │   ├── GetAllCoursesHandler.php
│   │   │   └── SearchCoursesHandler.php
│   │   ├── DTOs/              // Data Transfer Objects for input/output
│   │   │   ├── CourseDTO.php
│   │   │   ├── CourseSummaryDTO.php
│   │   │   └── CourseReadModel.php
│   │   └── Services/          // Shared validation or application services
│   │       └── CourseValidationService.php
│   ├── Infrastructure/        // Technical implementation details
│   │   ├── Repositories/      // Repository interfaces and implementations
│   │   │   ├── Contracts/
│   │   │   │   └── CourseRepositoryInterface.php
│   │   │   ├── Eloquent/
│   │   │   │   ├── EloquentCourseRepository.php
│   │   │   │   └── EloquentCourseReadRepository.php
│   │   │   └── Migrations/
│   │   │       └── 2024_01_01_000000_create_courses_table.php
│   │   ├── Messaging/         // Event bus implementations
│   │   │   ├── EventBusInterface.php
│   │   │   ├── InMemoryEventBus.php
│   │   │   ├── RabbitMQEventBus.php
│   │   │   ├── KafkaEventBus.php
│   │   │   └── Handlers/
│   │   │       ├── SendEmailOnCourseCreated.php
│   │   │       └── UpdateCacheOnCourseUpdated.php
│   │   ├── Providers/         // Service providers for DI
│   │   │   └── CourseManagementServiceProvider.php
│   │   └── Queries/           // Query builders for persistence
│   │       └── CourseQueryBuilder.php
│   └── Presentation/          // User-facing interfaces
│       ├── Controllers/       // Controllers for handling requests
│       │   └── CourseController.php
│       ├── Requests/          // Request validation and input formatting
│       │   ├── CreateCourseRequest.php
│       │   ├── UpdateCourseRequest.php
│       │   └── DeleteCourseRequest.php
│       ├── Resources/         // Views and translations
│       │   ├── views/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   └── lang/
│       │       └── en/
│       │           └── courses.php
│       └── Routes/            // Routes for web and API endpoints
│           ├── web.php
│           └── api.php
├── tests/                     // Automated tests
│   ├── Feature/               // High-level feature tests
│   │   └── CourseControllerTest.php
│   ├── Unit/                  // Unit tests for individual layers
│   │   ├── Domain/
│   │   │   ├── Entities/
│   │   │   ├── ValueObjects/
│   │   │   └── Services/
│   │   ├── Application/
│   │   │   ├── CommandHandlers/
│   │   │   ├── QueryHandlers/
│   │   │   └── DTOs/
│   │   ├── Infrastructure/
│   │   │   ├── Messaging/
│   │   │   ├── Persistence/
│   │   │   └── Queries/
│   │   └── Events/
│   │       ├── Handlers/
│   │       └── Subscribers/
│   └── Presentation/
│       ├── Controllers/
│       └── Requests/
assessment-management-module/  // Another module (details omitted)
└── composer.json              // Dependency management for this module
learning-system-module/        // Another module (details omitted)
└── composer.json              // Dependency management for this module

```
