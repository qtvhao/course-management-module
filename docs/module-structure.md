Dưới đây là cấu trúc module “Quản lý khóa học” (Course Management)

```plaintext
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
│   │   │   ├── Services/          // Các interface service (optional)
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
│   │       ├── AuthorizationService.php
│   │       └── ...
│   ├── Application/               // Application logic for use cases and queries
│   │   ├── Commands/              // Data structures for command operations
│   │   │   ├── CreateCourseCommand.php
│   │   │   ├── UpdateCourseCommand.php
│   │   │   └── DeleteCourseCommand.php
│   │   ├── CommandHandlers/       // Command handlers
│   │   │   ├── CreateCourseHandler.php
│   │   │   ├── UpdateCourseHandler.php
│   │   │   └── DeleteCourseHandler.php
│   │   ├── Contracts/
│   │   │   ├── CommandBusInterface.php
│   │   │   └── EventHandlerInterface.php
│   │   ├── DataQueries/           // Data structures for read operations
│   │   │   ├── Criteria/          // Query criteria
│   │   │   │   ├── CourseCriteria.php
│   │   │   │   ├── PaginationCriteria.php
│   │   │   │   └── SortingCriteria.php
│   │   │   ├── GetCourseByIdQuery.php
│   │   │   ├── GetAllCoursesQuery.php
│   │   │   ├── GetPaginatedCoursesQuery.php
│   │   │   └── SearchCoursesQuery.php
│   │   ├── QueryHandlers/         // Handlers for queries
│   │   │   ├── GetCourseByIdHandler.php
│   │   │   ├── GetAllCoursesHandler.php
│   │   │   ├── GetPaginatedCoursesHandler.php
│   │   │   └── SearchCoursesHandler.php
│   │   ├── DTOs/                  // Data transfer objects
│   │   │   ├── CourseDTO.php
│   │   │   ├── CourseSummaryDTO.php
│   │   │   ├── PaginatedCoursesDTO.php
│   │   │   └── CourseReadModel.php
│   │   ├── EventHandlers/         // Event handling logic
│   │   │   ├── SendEmailOnCourseCreated.php
│   │   │   └── UpdateCacheOnCourseUpdated.php
│   │   └── EventDispatcher.php    // Centralized event dispatcher
│   ├── Infrastructure/            // Technical implementation details
│   │   ├── Messaging/             // Event buses and event handling
│   │   │   ├── InMemoryCommandBus.php
│   │   │   ├── QueueCommandBus.php // Command bus implementation using Laravel queues
│   │   │   ├── RabbitMQCommandBus.php
│   │   │   ├── KafkaCommandBus.php
│   │   │   ├── ...
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
│   ├── Traits                      // Reusable traits
│   │   └── RecordsDomainEvents.php // Domain event recording (dùng cho Aggregate)
│   ├── Contracts/                  // Interfaces (contracts) của các lớp cơ sở
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

---

interface AggregateRootInterface
{
    /**
     * Release all domain events that were recorded by the aggregate.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents(): array;

    /**
     * Record a new domain event.
     *
     * @param DomainEvent $event
     * @return void
     */
    public function recordEvent(DomainEvent $event): void;
}

interface CommandBusInterface
{
    /**
     * Dispatch a command to its handler.
     */
    public function dispatch(object $command, $priority): void;
}

interface EventBusInterface
{
    /**
     * Publish a single event to be handled.
     *
     * @param object $event
     * @return void
     */
    public function publish(object $event): void;

    /**
     * Register an event handler for a specific event.
     *
     * @param string $eventClass
     * @param callable $handler
     * @return void
     */
    public function subscribe(string $eventClass, callable $handler): void;
}

interface EventHandlerInterface
{
    /**
     * Handle the given domain event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void;
}

### **Tóm Tắt Kiến Trúc Module Structure**

Module structure được thiết kế dựa trên các nguyên tắc kiến trúc phần mềm hiện đại nhằm tạo nên một hệ thống **dễ bảo trì, mở rộng và linh hoạt**. Các concept cốt lõi được áp dụng như sau:

* * * * *

### **1\. Ports and Adapters Architecture**

-   **Ports** (Contracts/Interfaces): Định nghĩa cách hệ thống tương tác với các công cụ bên ngoài, đảm bảo Application Core không phụ thuộc vào implementation cụ thể.
-   **Adapters** (Infrastructure): Hiện thực hóa Ports, kết nối với các công cụ như database (`EloquentCourseWriteRepository`) hay message queue (`RabbitMQCommandBus`).

* * * * *

### **2\. Domain-Driven Design (DDD)**

-   **Domain Layer**:
    -   **Entities**: Thực thể miền, đại diện cho dữ liệu và logic chính (e.g., `Course`).
    -   **Value Objects**: Thuộc tính bất biến như `CourseId` đảm bảo tính toàn vẹn dữ liệu.
    -   **Domain Events**: Quản lý thay đổi trong miền (e.g., `CourseCreatedEvent`).
    -   **Domain Services**: Xử lý logic phức tạp, không thuộc về một thực thể nào (e.g., `ScheduleValidationService`).

* * * * *

### **3\. Command-Query Responsibility Segregation (CQRS)**

-   **Commands**: Xử lý các thao tác write (e.g., `CreateCourseCommand`).
-   **Queries**: Đọc dữ liệu (e.g., `SearchCoursesQuery`).
-   **Handlers**: Tách riêng logic xử lý của Commands và Queries.

* * * * *

### **4\. Event-Driven Architecture (EDA)**

-   **Event Bus**: Trung gian phân phối các sự kiện giữa các module (e.g., `EventDispatcher`).
-   **Event Handlers**: Xử lý các side effects như gửi email (`SendEmailOnCourseCreated`) hoặc cập nhật cache.

* * * * *

### **5\. Layered Architecture**

-   Chia hệ thống thành 4 lớp:
    -   **Presentation**: Controllers, Requests xử lý giao diện người dùng.
    -   **Application**: Use cases (commands, queries, handlers).
    -   **Domain**: Logic nghiệp vụ cốt lõi.
    -   **Infrastructure**: Tích hợp công cụ bên ngoài (ORM, Message Bus).

* * * * *

### **6\. Componentization**

-   **Package by Component**: Phân tách code theo domain hoặc feature (e.g., `course-management-module`).
-   **Shared Kernel**: Chia sẻ các thành phần tái sử dụng như `BaseEvent`, `BaseRepositoryInterface`.

* * * * *

### **Kết Luận**

Đây là một kiến trúc hiện đại, phù hợp cho các dự án phức tạp và yêu cầu phát triển lâu dài.

Cấu trúc module này kết hợp các nguyên tắc **Ports & Adapters**, **DDD**, **CQRS**, và **EDA** để đạt được các lợi ích gì?

```
