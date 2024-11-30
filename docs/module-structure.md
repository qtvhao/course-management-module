Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```plaintext
This module structure adhered DDD, Clean Architecture, CQRS?
course-management-module/
├── composer.json                  // Dependency configuration, autoloading Qtvhao\CourseManagement namespace
├── docs/
│   └── README.md                  // Module documentation
├── src/
│   ├── Domain/                    // Core business logic
│   │   ├── Aggregates/            // Aggregate roots
│   │   │   ├── CourseAggregate.php // Aggregate root for Course
│   │   │   └── CourseModuleAggregate.php
│   │   ├── Contracts/
│   │   │   ├── Repositories/      // Các interface repository
│   │   │   │   ├── CourseWriteRepositoryInterface.php
│   │   │   │   ├── CourseReadRepositoryInterface.php
│   │   │   │   └── CourseSearchRepositoryInterface.php
│   │   │   ├── Services/          // Các interface service
│   │   │   │   └── CourseDomainServiceInterface.php
│   │   │   └── Handlers/          // Các contract cho event/command/query handler
│   │   │       ├── CommandHandlerInterface.php
│   │   │       └── QueryHandlerInterface.php
│   │   ├── Entities/              // Core domain objects
│   │   │   └── Course.php
│   │   ├── Events/                // Domain events
│   │   │   ├── CourseCreatedEvent.php
│   │   │   ├── CourseUpdatedEvent.php
│   │   │   └── CourseDeletedEvent.php
│   │   ├── QueriesDefinitions/    // CQRS Query definitions
│   │   │   └── SearchCoursesQuery.php
│   │   ├── ValueObjects/          // Immutable domain properties
│   │   │   ├── CourseId.php
│   │   │   ├── CourseTitle.php
│   │   │   └── CourseDuration.php
│   │   └── Services/              // Domain-specific services
│   │       ├── PrerequisiteValidationService.php
│   │       ├── ProgressCalculationService.php
│   │       ├── ScheduleValidationService.php
│   │       ├── RelatedCoursesValidationService.php
│   │       ├── CourseUpdateValidationService.php
│   │       └── ...
│   ├── Application/               // Application logic for use cases and queries
│   │   ├── UseCases/              // Specific use cases (create, update, delete)
│   │   │   ├── CreateCourseUseCase.php
│   │   │   ├── UpdateCourseUseCase.php
│   │   │   └── DeleteCourseUseCase.php
│   │   ├── Commands/              // Data structures for command operations
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   └── DeleteCourseCommand.php
│   │   ├── CommandHandlers/       // Command handlers
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   └── DeleteCourseHandler.php
│   │   ├── DataQueries/           // Data structures for read operations
│   │   │   ├── Criteria/          // Query criteria
│   │   │   │   ├── CourseCriteria.php
│   │   │   │   ├── PaginationCriteria.php
│   │   │   │   └── SortingCriteria.php
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
│   │   ├── EventHandlers/         // Event handling logic
│   │   │   ├── SendEmailOnCourseCreated.php
│   │   │   └── UpdateCacheOnCourseUpdated.php
│   │   └── EventDispatcher.php    // Centralized event dispatcher
│   ├── Infrastructure/            // Technical implementation details
│   │   ├── Messaging/             // Event buses and event handling
│   │   │   ├── EventBusInterface.php
│   │   │   ├── InMemoryEventBus.php
│   │   │   ├── RabbitMQEventBus.php
│   │   │   └── KafkaEventBus.php
│   │   ├── Persistence/           // Database and search integrations
│   │   │   ├── Eloquent/          // Eloquent-specific implementations
│   │   │   │   ├── EloquentCourseWriteRepository.php
│   │   │   │   └── EloquentCourseReadRepository.php
│   │   │   ├── Search/            // Elasticsearch integration
│   │   │   │   ├── CourseSearchIndexer.php
│   │   │   │   ├── CourseSearchRepository.php
│   │   │   │   └── SearchResult.php
│   │   │   ├── DBQueries/         // Query builder and query-related logic
│   │   │   │   └── CourseQueryBuilder.php
│   │   │   └── Migrations/        // Database migrations
│   │   │       └── 2024_01_01_000000_create_courses_table.php
│   │   ├── Services/              // External services (e.g., email, cache)
│   │   │   └── EmailService.php
│   │   └── Providers/             // Service providers for dependency injection
│   │       └── CourseManagementServiceProvider.php
│   ├── Presentation/              // User-facing interfaces (API and Web)
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
│   │   └── Routes/                // Route definitions (web and API)
│   │       ├── web.php
│   │       └── api.php
│   └── Helpers/                   // Utility functions
├── tests/                         // Automated tests, autoloading Qtvhao\CourseManagement\Tests namespace
│   ├── Feature/                   // End-to-end functionality tests
│   │   └── CourseControllerTest.php
│   ├── Unit/                      // Unit tests for components
│   │   ├── Domain/
│   │   │   ├── Aggregates/
│   │   │   ├── Entities/
│   │   │   ├── Events/
│   │   │   ├── ValueObjects/
│   │   │   └── Services/
│   │   ├── Application/
│   │   │   ├── UseCases/
│   │   │   ├── Commands/
│   │   │   ├── CommandHandlers/
│   │   │   ├── DataQueries/
│   │   │   ├── QueryHandlers/
│   │   │   ├── DTOs/
│   │   │   └── EventHandlers/
│   │   ├── Infrastructure/
│   │   │   ├── Messaging/
│   │   │   ├── Persistence/
│   │   │   │   ├── Eloquent/
│   │   │   │   ├── Search/
│   │   │   │   ├── DBQueries/
│   │   │   │   └── Migrations/
│   │   │   ├── Services/
│   │   │   └── Providers/
│   │   └── Presentation/
│   │       ├── Controllers/
│   │       ├── Requests/
│   │       ├── Resources/
│   │       └── Routes/
│   └── TestCase.php               // Base test case class
└── bootstrap/                     // Initialization files
    └── EventHandlersBootstrap.php // Event handler registrations
shared-module/
├── src/
│   ├── Contracts/                 // Interfaces (contracts) của các lớp cơ sở
│   │   ├── BaseServiceInterface.php
│   │   ├── BaseModelInterface.php
│   │   ├── BaseEventInterface.php
│   │   ├── BaseListenerInterface.php
│   │   ├── BaseDTOInterface.php
│   │   ├── BaseMiddlewareInterface.php
│   │   └── BaseExceptionInterface.php
│   ├── Services/
│   │   ├── BaseService.php
│   │   └── ServiceWithContract.php // Service triển khai theo interface
│   ├── Models/
│   │   └── BaseModel.php
│   ├── Repositories/
│   │   ├── BaseRepositoryInterface.php
│   │   └── EloquentBaseRepository.php
│   ├── Events/
│   │   ├── BaseEvent.php
│   │   └── SpecificEvent.php       // Event kế thừa BaseEvent
│   ├── Middleware/
│   │   └── BaseMiddleware.php
│   ├── Exceptions/
│   │   └── BaseException.php
│   ├── DTOs/
│   │   └── BaseDTO.php
│   └── Helpers/
└── tests/
    └── ...
assessment-management-module/  // Another module (details omitted)
└── composer.json              // Dependency management for this module
learning-system-module/        // Another module (details omitted)
└── composer.json              // Dependency management for this module

```
