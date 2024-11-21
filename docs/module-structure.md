Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```
course-management-module/
├── composer.json                  // Dependency configuration
├── docs/
│   └── README.md                  // Module documentation
├── src/
│   ├── Domain/                    // Core business logic
│   │   ├── Entities/              // Core domain objects
│   │   │   └── Course.php
│   │   ├── Events/                // Domain events
│   │   │   ├── CourseCreatedEvent.php
│   │   │   ├── CourseUpdatedEvent.php
│   │   │   └── CourseDeletedEvent.php
│   │   ├── Repositories/          // Interfaces for read and write repositories
│   │   │   ├── CourseWriteRepositoryInterface.php
│   │   │   └── CourseReadRepositoryInterface.php
│   │   ├── ValueObjects/          // Immutable domain properties
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   └── CourseDuration.php
│   │   └── Services/              // Domain-specific services
│   │       └── CourseDomainService.php
│   ├── Application/               // Application layer for use cases and coordination
│   │   ├── UseCases/              // Specific use cases (create, update, delete course)
│   │   │   ├── CreateCourseUseCase.php
│   │   │   ├── UpdateCourseUseCase.php
│   │   │   └── DeleteCourseUseCase.php
│   │   ├── Commands/              // Data structures for write operations
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   └── DeleteCourseCommand.php
│   │   ├── CommandHandlers/       // Handlers for commands
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   └── DeleteCourseHandler.php
│   │   ├── Queries/               // Data structures for read operations
│   │   │   ├── GetCourseByIdQuery.php
│   │   │   ├── GetAllCoursesQuery.php
│   │   │   └── SearchCoursesQuery.php
│   │   ├── QueryHandlers/         // Handlers for queries
│   │   │   ├── GetCourseByIdHandler.php
│   │   │   ├── GetAllCoursesHandler.php
│   │   │   └── SearchCoursesHandler.php
│   │   ├── DTOs/                  // Data transfer objects
│   │   │   ├── CourseDTO.php
│   │   │   ├── CourseSummaryDTO.php
│   │   │   └── CourseReadModel.php
│   │   ├── EventHandlers/         // Handlers for domain events
│   │   │   ├── SendEmailOnCourseCreated.php
│   │   │   └── UpdateCacheOnCourseUpdated.php
│   │   └── EventDispatcher.php    // Centralized event dispatcher
│   ├── Infrastructure/            // Technical implementation details
│   │   ├── Messaging/             // Event buses and technical event handling
│   │   │   ├── EventBusInterface.php
│   │   │   ├── InMemoryEventBus.php
│   │   │   ├── RabbitMQEventBus.php
│   │   │   ├── KafkaEventBus.php
│   │   └── Persistence/           // Database layer
│   │       ├── Eloquent/          // Eloquent-specific implementations
│   │       │   ├── EloquentCourseWriteRepository.php
│   │       │   └── EloquentCourseReadRepository.php
│   │       └── Migrations/        // Database migrations
│   │           └── 2024_01_01_000000_create_courses_table.php
│   ├── Presentation/              // User-facing interfaces
│   │   ├── Controllers/           // Application controllers
│   │   │   └── CourseController.php
│   │   ├── Requests/              // Request validation and formatting
│   │   │   ├── CreateCourseRequest.php
│   │   │   ├── UpdateCourseRequest.php
│   │   │   └── DeleteCourseRequest.php
│   │   ├── Resources/             // Views, translations, and static files
│   │   │   ├── views/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   └── lang/
│   │   │       └── en/
│   │   │           └── courses.php
│   │   └── Routes/                // Route definitions
│   │       ├── web.php
│   │       └── api.php
├── tests/                         // Automated tests
│   ├── Feature/                   // End-to-end tests
│   │   └── CourseControllerTest.php
│   ├── Unit/                      // Unit tests for each layer
│   │   ├── Domain/
│   │   ├── Application/
│   │   ├── Infrastructure/
│   │   └── EventHandlers/
└── bootstrap/                     // Initialization files
    └── EventHandlersBootstrap.php // Event handler registrations
assessment-management-module/  // Another module (details omitted)
└── composer.json              // Dependency management for this module
learning-system-module/        // Another module (details omitted)
└── composer.json              // Dependency management for this module

```
