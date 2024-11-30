Khi thực hiện Test-Driven Development (TDD) cho Application Layer, việc viết test nên tuân theo một trình tự logic để đảm bảo bạn phát triển phần mềm theo đúng yêu cầu và tăng cường tính ổn định. Thứ tự nên như sau:

### 1. Test các Use Case chính (Business Logic cấp cao)

- Mục tiêu: Đảm bảo các Use Cases trong Application Layer (ví dụ: CreateCourseUseCase, UpdateCourseUseCase) hoạt động đúng theo yêu cầu nghiệp vụ.
- Tại sao: Đây là các chức năng cốt lõi mà module cần thực hiện, chúng thường đại diện cho các hành động chính của người dùng.
- Cách làm:
	- Viết test case kiểm tra các kết quả mong đợi từ các use case.
	- Mock các dependencies bên dưới (Repositories, Services) để tập trung kiểm tra logic chính.
	- Test các trường hợp thành công (happy path) trước.
	- Thêm test cho các trường hợp đặc biệt hoặc lỗi (edge cases).

Ví dụ:
Kiểm tra CreateCourseUseCase:
```php
public function testCreateCourseSuccessfully()
{
    $command = new CreateCourseCommand('Title', 'Description', 10);

    $mockRepository = $this->createMock(CourseWriteRepositoryInterface::class);
    $mockRepository->expects($this->once())->method('save');

    $useCase = new CreateCourseUseCase($mockRepository);
    $result = $useCase->execute($command);

    $this->assertTrue($result->isSuccess());
}
```
2. Test Command Handlers

- Mục tiêu: Đảm bảo rằng các Command Handlers xử lý chính xác dữ liệu từ Command và kích hoạt các actions trong Domain Layer.
- Tại sao: Command Handlers là trung gian giữa tầng Application và Domain. Chúng đảm bảo dữ liệu được chuyển đổi và xử lý chính xác.
- Cách làm:
- Kiểm tra xem Command Handler gọi đúng các phương thức trong Use Case hoặc Service.
- Mock các đối tượng Domain hoặc Repository để kiểm tra tính tương tác.

Ví dụ:
Kiểm tra CreateCourseHandler gọi đúng use case:

```php
public function testCreateCourseHandlerCallsUseCase()
{
    $command = new CreateCourseCommand('Title', 'Description', 10);

    $mockUseCase = $this->createMock(CreateCourseUseCase::class);
    $mockUseCase->expects($this->once())->method('execute')->with($command);

    $handler = new CreateCourseHandler($mockUseCase);
    $handler->handle($command);
}
```
3. Test Queries và Query Handlers

- Mục tiêu: Đảm bảo các Queries trả về kết quả chính xác từ nguồn dữ liệu.
- Tại sao: Queries là phần không thể thiếu để hiển thị dữ liệu, đặc biệt trong kiến trúc CQRS.
- Cách làm:
- Viết test để kiểm tra các Query Handlers trả về đúng kiểu dữ liệu hoặc DTO.
- Mock Read Repositories để kiểm tra tính tương tác.

Ví dụ:
Kiểm tra GetCourseByIdHandler:

```php
public function testGetCourseByIdHandlerReturnsCourseDTO()
{
    $query = new GetCourseByIdQuery('course-id');

    $mockRepository = $this->createMock(CourseReadRepositoryInterface::class);
    $mockRepository->method('findById')->willReturn(new Course('course-id', 'Title'));

    $handler = new GetCourseByIdHandler($mockRepository);
    $result = $handler->handle($query);

    $this->assertInstanceOf(CourseDTO::class, $result);
}
```

4. Test Event Handlers (nếu có)

- Mục tiêu: Đảm bảo các Event Handlers thực hiện đúng hành động khi nhận được sự kiện từ Domain Layer.
- Tại sao: Event Handlers thường kích hoạt các logic ngoại vi như gửi email, cập nhật cache.
- Cách làm:
- Viết test để kiểm tra event được xử lý chính xác.
- Mock các dịch vụ bên ngoài như EmailService hoặc CacheService.

Ví dụ:
Kiểm tra handler gửi email khi sự kiện được kích hoạt:

```php
public function testSendEmailOnCourseCreated()
{
    $event = new CourseCreatedEvent('course-id', 'Course Title');

    $mockEmailService = $this->createMock(EmailService::class);
    $mockEmailService->expects($this->once())->method('send');

    $handler = new SendEmailOnCourseCreated($mockEmailService);
    $handler->handle($event);
}
```

5. Test Edge Cases và Error Handling

- Mục tiêu: Đảm bảo mọi trường hợp lỗi, ngoại lệ được xử lý hợp lý.
- Tại sao: Để bảo vệ ứng dụng khỏi lỗi không mong muốn và cải thiện trải nghiệm người dùng.
- Cách làm:
- Test các trường hợp dữ liệu không hợp lệ (invalid data).
- Kiểm tra việc xử lý ngoại lệ (e.g., khi repository không tìm thấy dữ liệu).

Ví dụ:
Kiểm tra lỗi khi không tìm thấy course:

```php
public function testGetCourseByIdThrowsExceptionIfNotFound()
{
    $query = new GetCourseByIdQuery('invalid-id');

    $mockRepository = $this->createMock(CourseReadRepositoryInterface::class);
    $mockRepository->method('findById')->willReturn(null);

    $handler = new GetCourseByIdHandler($mockRepository);

    $this->expectException(CourseNotFoundException::class);
    $handler->handle($query);
}
```
Thứ tự tổng quát:

	1.	Use Cases – Xây dựng cốt lõi logic nghiệp vụ.
	2.	Command Handlers – Đảm bảo xử lý command đúng cách.
	3.	Query Handlers – Đảm bảo xử lý query và trả về dữ liệu chính xác.
	4.	Event Handlers – Xử lý sự kiện phát sinh.
	5.	Edge Cases & Exceptions – Đảm bảo hệ thống không bị lỗi do ngoại lệ hoặc dữ liệu xấu.

Lưu ý:

- TDD luôn tuân theo chu trình: Red -> Green -> Refactor.
	1.	Viết test case thất bại (Red).
	2.	Implement code để test thành công (Green).
	3.	Tối ưu hóa hoặc tái cấu trúc (Refactor).
- Bắt đầu với các yêu cầu đơn giản nhất (happy path), sau đó mở rộng sang các tình huống phức tạp hơn.
