### Bảng 1: Các Domain Services và Mục Đích của Chúng

Bảng này liệt kê các Domain Services với mục đích cụ thể và lý do vì sao chúng thuộc về Domain Service.

| Domain Service                    | Mục đích 																						| Vì sao nó thuộc về Domain Service? 																								|
|-----------------------------------|-----------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------|
| PrerequisiteValidationService 	| Kiểm tra các điều kiện tiên quyết của khóa học (ví dụ: tránh phụ thuộc vòng lặp).				| Xử lý các quy tắc kinh doanh liên quan đến nhiều thực thể (CourseId[]) và không thuộc về riêng bất kỳ một thực thể nào. 			|
| ProgressCalculationService 		| Tính toán tiến độ hoàn thành của học viên trong khóa học.	 									| Là logic tính toán liên quan đến nhiều ValueObjects (module đã hoàn thành và tổng số module). Thuần túy và độc lập với hạ tầng. 	|
| ScheduleValidationService 		| Xác minh lịch trình của khóa học, đảm bảo không bị chồng chéo.	 							| Là quy tắc kinh doanh cần so sánh giữa các ValueObjects (Schedule) và không liên quan đến cơ sở dữ liệu hay hệ thống khác. 		|
| RelatedCoursesValidationService 	| Kiểm tra các mối quan hệ giữa các khóa học, như điều kiện tiên quyết hoặc khóa học kế tiếp.   | Các quy tắc này ảnh hưởng đến nhiều thực thể (CourseAggregate và danh sách khóa học liên quan), phù hợp với tầng miền. 			|
| CourseUpdateValidationService 	| Kiểm tra và đảm bảo các cập nhật khóa học tuân theo quy tắc miền.	 							| Phối hợp logic kiểm tra trên các thuộc tính của thực thể, tránh các thay đổi làm hỏng tính nhất quán trong miền. 					|

### Bảng 2: Các Phương Thức Bị Loại Bỏ và Lý Do

Bảng này giải thích tại sao một số phương thức bị loại khỏi Domain Service, nêu rõ rằng chúng thuộc về các lớp hoặc tầng khác.

| Phương thức				| Mục đích																				| Lý do không thuộc về Domain Service																								|
|---------------------------|---------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------|
| verifyCourseAvailability	| Xác minh khóa học có thể đăng ký không (dựa vào cơ sở dữ liệu hoặc hệ thống khác).	| Phụ thuộc vào dữ liệu từ cơ sở hạ tầng (database) và các hệ thống khác, nên thuộc về Application Layer hoặc Infrastructure Layer.	|
| triggerNotifications		| Gửi thông báo đến người dùng khi có sự kiện liên quan đến khóa học.					| Xử lý thông báo là vấn đề của hạ tầng (gửi email, tin nhắn) và không thuộc về logic thuần túy của miền.							|
| assignInstructors			| Gán giảng viên cho khóa học dựa trên dữ liệu và điều kiện.							| Phụ thuộc vào các hệ thống bên ngoài (danh sách giảng viên, cơ sở dữ liệu), nên thuộc về Application Layer.						|
| validateCourseCapacity	| Kiểm tra xem số lượng học viên đã đạt giới hạn tối đa chưa.							| Phụ thuộc vào dữ liệu từ cơ sở dữ liệu (số lượng học viên đăng ký), không thể thực hiện trong tầng miền.							|

### Giới hạn Vai Trò của Domain Services

- Thuộc về Domain Service:
    - Logic cần xử lý nhiều thực thể hoặc giá trị miền nhưng không liên quan đến hạ tầng.
    - Logic là “thuần túy miền” (domain-pure), không phụ thuộc vào trạng thái bên ngoài (database, API, hệ thống khác).
    - Logic không nằm gọn trong một thực thể hoặc giá trị đối tượng mà cần sự phối hợp giữa chúng.
- Không thuộc về Domain Service:
    - Logic phụ thuộc vào hạ tầng hoặc các hệ thống bên ngoài (cơ sở dữ liệu, APIs).
    - Logic liên quan đến tác vụ kỹ thuật (gửi email, gán tài nguyên từ cơ sở dữ liệu).
    - Logic thuộc về Application Layer để thực thi các trường hợp sử dụng (use cases).

Bằng cách phân tách rõ ràng, bạn có thể duy trì sự tập trung và tinh gọn cho từng tầng trong kiến trúc Domain-Driven Design (DDD).
```php
<?php

namespace Qtvhao\CourseManagement\Domain\Services;

use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;

class PrerequisiteValidationService
{
    public function validate(CourseId $courseId, array $prerequisiteIds): bool
    {
        // Check for circular dependencies
        if (in_array($courseId, $prerequisiteIds)) {
            throw new PrerequisiteValidationException("Circular dependency detected.");
        }

        return true; // Business rules passed
    }
}
```

```php
<?php

namespace Qtvhao\CourseManagement\Application\UseCases;

use Qtvhao\CourseManagement\Domain\Contracts\Repositories\CourseReadRepositoryInterface;
use Qtvhao\CourseManagement\Domain\Services\PrerequisiteValidationService;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;

class ValidatePrerequisitesUseCase
{
    private CourseReadRepositoryInterface $repository;
    private PrerequisiteValidationService $validationService;

    public function __construct(
        CourseReadRepositoryInterface $repository,
        PrerequisiteValidationService $validationService
    ) {
        $this->repository = $repository;
        $this->validationService = $validationService;
    }

    public function execute(CourseId $courseId, array $prerequisiteIds): bool
    {
        // Fetch data
        $prerequisites = $this->repository->getPrerequisites($courseId);

        // Validate
        return $this->validationService->validate($courseId, $prerequisites);
    }
}
```


```php
<?php

namespace Tests\Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use Qtvhao\CourseManagement\Domain\Services\PrerequisiteValidationService;
use Qtvhao\CourseManagement\Domain\ValueObjects\CourseId;
use Qtvhao\CourseManagement\Domain\Exceptions\PrerequisiteValidationException;

class PrerequisiteValidationServiceTest extends TestCase
{
    private PrerequisiteValidationService $service;

    protected function setUp(): void
    {
        $this->service = new PrerequisiteValidationService();
    }

    public function testValidateReturnsTrueForValidPrerequisites()
    {
        // Arrange
        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-2'), new CourseId('course-3')];

        // Act
        $result = $this->service->validate($courseId, $prerequisiteIds);

        // Assert
        $this->assertTrue($result, 'Validation should return true for valid prerequisites.');
    }

    public function testValidateThrowsExceptionForCircularDependency()
    {
        // Arrange
        $courseId = new CourseId('course-1');
        $prerequisiteIds = [new CourseId('course-1')]; // Circular dependency

        // Assert
        $this->expectException(PrerequisiteValidationException::class);

        // Act
        $this->service->validate($courseId, $prerequisiteIds);
    }
}
```
