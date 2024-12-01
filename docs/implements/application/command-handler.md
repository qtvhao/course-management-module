Để mở rộng chức năng của UpdateCoursePrerequisiteHandler theo CQRS, DDD, và Clean Architecture, sử dụng phương pháp releaseEvents(), ta sẽ bổ sung logic xử lý các sự kiện domain được phát sinh trong quá trình cập nhật prerequisites.

### Bước 1: Thiết kế releaseEvents trong Aggregate

Aggregate (CourseAggregate)

Aggregate sẽ quản lý các sự kiện được ghi lại trong quá trình xử lý logic domain.

```php
class CourseAggregate implements AggregateRootInterface
{
    private array $domainEvents = []; // Store domain events.

    public function updatePrerequisites(array $prerequisiteIds): void
    {
        if ($this->arePrerequisitesValid($prerequisiteIds)) {
            $this->prerequisites = $prerequisiteIds;

            // Record domain event.
            $this->recordEvent(new PrerequisitesUpdatedEvent($this->id, $prerequisiteIds));
        } else {
            throw new InvalidPrerequisiteException("Invalid prerequisites provided.");
        }
    }

    public function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = []; // Clear recorded events after releasing.
        return $events;
    }
}
```

### Bước 2: Mở rộng UpdateCoursePrerequisiteHandler

Command Handler

UpdateCoursePrerequisiteHandler sẽ xử lý lệnh cập nhật và phát hành các sự kiện domain thông qua releaseEvents().

```php
class UpdateCoursePrerequisiteHandler
{
    public function __construct(
        private CourseWriteRepositoryInterface $repository,
        private EventBusInterface $eventBus // Event bus để phát hành sự kiện.
    ) {}

    public function handle(UpdatePrerequisitesCommand $command): void
    {
        // Load the aggregate root (Course) from repository.
        $course = $this->repository->findById($command->courseId);

        if (!$course) {
            throw new NotFoundException("Course not found.");
        }

        // Update prerequisites using domain logic.
        $course->updatePrerequisites($command->prerequisiteIds);

        // Persist changes in the repository.
        $this->repository->save($course);

        // Release and publish domain events.
        foreach ($course->releaseEvents() as $event) {
            $this->eventBus->publish($event);
        }
    }
}
```

### Bước 3: Triển khai Event Handling

Các sự kiện domain sẽ được phát hành và xử lý bởi các event handlers.

Domain Event

Sự kiện đại diện cho việc cập nhật prerequisites:

```php
class PrerequisitesUpdatedEvent extends DomainEvent
{
    public function __construct(
        public string $courseId,
        public array $prerequisiteIds
    ) {}
}
```

Event Handlers

Xử lý các hành động liên quan khi sự kiện xảy ra:
1.	Gửi Email:

```php
class SendNotificationOnPrerequisitesUpdated
{
    public function handle(PrerequisitesUpdatedEvent $event): void
    {
        // Logic to send email notification.
        echo "Notification sent for course {$event->courseId}";
    }
}
```


2.	Cập nhật Cache:

```php
class UpdateCacheOnPrerequisitesUpdated
{
    public function handle(PrerequisitesUpdatedEvent $event): void
    {
        // Logic to update cache.
        echo "Cache updated for course {$event->courseId}";
    }
}
```



EventBus Interface:

Event bus sẽ phát hành sự kiện đến các handler:

```php
interface EventBusInterface
{
    public function publish(DomainEvent $event): void;
    public function subscribe(string $eventClass, callable $handler): void;
}
```
### Bước 4: Đăng ký Event Handlers

Event Dispatcher:

Cần đảm bảo các handler được đăng ký để xử lý các sự kiện:

```php
class InMemoryEventBus implements EventBusInterface
{
    private array $handlers = [];

    public function subscribe(string $eventClass, callable $handler): void
    {
        $this->handlers[$eventClass][] = $handler;
    }

    public function publish(DomainEvent $event): void
    {
        $eventClass = get_class($event);
        if (!empty($this->handlers[$eventClass])) {
            foreach ($this->handlers[$eventClass] as $handler) {
                $handler($event);
            }
        }
    }
}


// Registration example.
$eventBus = new InMemoryEventBus();
$eventBus->subscribe(PrerequisitesUpdatedEvent::class, [new SendNotificationOnPrerequisitesUpdated(), 'handle']);
$eventBus->subscribe(PrerequisitesUpdatedEvent::class, [new UpdateCacheOnPrerequisitesUpdated(), 'handle']);
```

### Bước 5: Kiến trúc tổng thể

1. **Presentation Layer:**
    - API nhận lệnh cập nhật từ client và chuyển đến `UpdateCoursePrerequisiteHandler`.

2. **Application Layer:**
    - `UpdateCoursePrerequisiteHandler` thực hiện cập nhật và phát hành sự kiện.

3. **Domain Layer:**
    - Aggregate (`CourseAggregate`) thực hiện logic cập nhật và ghi nhận sự kiện.
    - Các sự kiện được phát hành qua `releaseEvents()`.

4. **Infrastructure Layer:**
    - Repository cập nhật database.
    - Event bus phát hành sự kiện và gọi các handler (như gửi email, cập nhật cache).

### Kết quả

Với thiết kế trên:
- Tính tách biệt trách nhiệm (Separation of Concerns) được đảm bảo.
- Sự kiện domain (PrerequisitesUpdatedEvent) cung cấp cách tiếp cận event-driven để mở rộng chức năng mà không làm phức tạp handler.
- Hệ thống dễ dàng mở rộng thêm các handler mới khi cần xử lý các hành động khác, ví dụ: ghi log, đồng bộ hóa với hệ thống khác.

### Dòng xử lý tổng quan:

1. API -> Gửi `UpdatePrerequisitesCommand`.
2. `UpdateCoursePrerequisiteHandler` -> Cập nhật `Aggregate`, phát hành sự kiện domain.
3. Event bus -> Gọi các handler xử lý sự kiện.
4. Handlers -> Gửi email, cập nhật cache, hoặc thực hiện các hành động khác.
 
