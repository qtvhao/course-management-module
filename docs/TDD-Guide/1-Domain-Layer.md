Khi thực hiện Test-Driven Development (TDD) cho Domain Layer, việc tuân thủ thứ tự viết test hợp lý sẽ giúp bạn xây dựng các tính năng một cách incremental (từng bước) và đảm bảo rằng các quy tắc business logic được triển khai chính xác. Dưới đây là thứ tự hợp lý khi viết test:

### 1. Test các Value Objects trước

- Lý do: Value Objects là các thành phần cơ bản và bất biến trong domain. Chúng đảm bảo các giá trị trong hệ thống luôn hợp lệ.
- Cách thực hiện:
- Kiểm tra tính hợp lệ của các giá trị (validation logic).
- Đảm bảo tính bất biến (immutable) của các đối tượng.
- Ví dụ:

```php
public function test_valid_course_title(): void
{
    $title = new CourseTitle('Introduction to TDD');
    $this->assertEquals('Introduction to TDD', $title->value());
}

public function test_invalid_course_title_throws_exception(): void
{
    $this->expectException(InvalidArgumentException::class);
    new CourseTitle('');
}
```

### 2. Test các Domain Entities

- Lý do: Các entities là đối tượng chính trong domain, chứa dữ liệu và các hành vi nghiệp vụ.
- Cách thực hiện:
- Kiểm tra việc khởi tạo entity với các giá trị hợp lệ.
- Kiểm tra các hành vi hoặc phương thức được định nghĩa trong entity.
- Ví dụ:

```php
public function test_create_course_entity(): void
{
    $course = new Course(new CourseId('123'), new CourseTitle('TDD Basics'), new CourseDuration(10));
    $this->assertEquals('123', $course->id()->value());
    $this->assertEquals('TDD Basics', $course->title()->value());
    $this->assertEquals(10, $course->duration()->value());
}
```

### 3. Test các Domain Events

- Lý do: Domain events phản ánh các hành vi hoặc trạng thái quan trọng xảy ra trong hệ thống.
- Cách thực hiện:
- Kiểm tra sự kiện được kích hoạt chính xác.
- Đảm bảo thông tin đi kèm với sự kiện là đầy đủ và đúng.
- Ví dụ:

```php
public function test_course_created_event(): void
{
    $event = new CourseCreatedEvent('123', 'TDD Basics');
    $this->assertEquals('123', $event->courseId());
    $this->assertEquals('TDD Basics', $event->courseTitle());
}
```

### 4. Test các Aggregate Roots

- Lý do: Aggregate roots quản lý các entities con và đảm bảo các quy tắc nghiệp vụ phức tạp.
- Cách thực hiện:
- Test các hành vi của aggregate root, như việc thay đổi trạng thái hoặc xử lý các tương tác giữa các entity.
- Đảm bảo các invariants (quy tắc bất biến) luôn được giữ vững.
- Ví dụ:

```php
public function test_course_aggregate_handles_creation_event(): void
{
    $course = CourseAggregate::create('123', 'Advanced TDD', 15);
    $this->assertInstanceOf(CourseCreatedEvent::class, $course->releaseEvents()[0]);
}
```

### 5. Test các Domain Services (nếu có)

- Lý do: Domain services chứa logic nghiệp vụ không thuộc về bất kỳ entity hoặc value object nào.
- Cách thực hiện:
- Test các trường hợp sử dụng cụ thể được thực hiện bởi domain service.
- Ví dụ:

```php
public function test_calculate_course_price(): void
{
    $service = new CourseDomainService();
    $price = $service->calculatePrice(new CourseDuration(10), 100);
    $this->assertEquals(1000, $price);
}
```

### 6. Test các Integration giữa Entities và Domain Events
- Lý do:
    - Kiểm tra luồng hoạt động giữa Entity và các Domain Events.
- Cách thực hiện:
    - Đảm bảo rằng một sự kiện được tạo ra khi một hành động xảy ra trên Entity.
- Ví dụ:

```php
public function test_course_creation_dispatches_event()
{
    $course = new CourseAggregate(new CourseId(1), new CourseTitle('Intro to PHP'));
    $events = $course->pullDomainEvents();

    $this->assertInstanceOf(CourseCreatedEvent::class, $events[0]);
}
```

### 7. Viết Test cho Partitions hoặc Complex Scenarios (nếu có)

- Mục tiêu: Kiểm tra các logic đặc thù của domain, chẳng hạn như:
- Các trường hợp hiếm hoặc khó tái hiện.
- Các tương tác giữa nhiều phần trong Domain.

### 8. Refactor và tối ưu hóa

- Sau khi hoàn thành các bước trên, refactor mã nguồn để đảm bảo tính sạch sẽ (clean code).
- Xem lại các test đã viết để loại bỏ trùng lặp hoặc cải tiến coverage.

### Kết Luận

Thứ tự hợp lý trong TDD cho Domain Layer:
	1.	Value Objects → 2. Entities → 3. Domain Events → 4. Aggregate Roots → 5. Domain Services → 6. Integration Tests.

### Lợi ích của thứ tự này:

1.	Từng bước và rõ ràng: Tập trung vào các khối xây dựng nhỏ nhất trước, sau đó mới đến các thành phần phức tạp.
2.	Dễ bảo trì: Các lỗi được phát hiện ngay ở mức thấp, giảm rủi ro bug lan sang các lớp cao hơn.
3.	Bám sát logic nghiệp vụ: Các test phản ánh chính xác cách nghiệp vụ được triển khai trong domain.
