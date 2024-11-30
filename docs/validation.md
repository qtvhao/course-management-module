Trong cấu trúc của module này, validation cần được đặt ở các vị trí phù hợp tùy thuộc vào mục tiêu và loại validation, đồng thời tuân thủ các nguyên tắc của DDD, Clean Architecture, và CQRS. Sau đây là cách tổ chức hợp lý:

### 1. Validation tại Presentation Layer

- Mục tiêu:
- Xác thực cấu trúc và dữ liệu nhập từ người dùng (e.g., API request, form submission).
- Kiểm tra xem dữ liệu đã đầy đủ và đúng định dạng trước khi chuyển vào ứng dụng.
- Vị trí:
- Trong thư mục Presentation/Requests/, ví dụ:
- CreateCourseRequest.php
- UpdateCourseRequest.php
- Các lớp này thường sử dụng framework (ví dụ: Laravel’s Form Request) để kiểm tra và phản hồi nhanh chóng nếu dữ liệu không hợp lệ.
- Ví dụ:

```php
class CreateCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ];
    }
}
```

- Ưu điểm:
- Loại bỏ các lỗi nhập liệu trước khi logic nghiệp vụ chạy.
- Giữ domain layer sạch, không bị ảnh hưởng bởi các lỗi cơ bản từ phía người dùng.

### 2. Validation tại Application Layer

- Mục tiêu:
- Kiểm tra logic nghiệp vụ liên quan đến các use case (cách dữ liệu được sử dụng trong ngữ cảnh cụ thể).
- Ví dụ: kiểm tra xem một Course Title có trùng lặp không trước khi tạo mới.
- Vị trí:
- Trong các lớp UseCases/ hoặc Services/ thuộc Application/.
- Validation tại đây có thể sử dụng các domain services hoặc repositories để thực hiện kiểm tra logic phức tạp.
- Ví dụ:

```php
class CreateCourseUseCase
{
    public function __construct(private CourseWriteRepositoryInterface $repository) {}

    public function execute(CreateCourseCommand $command): void
    {
        if ($this->repository->existsByTitle($command->title)) {
            throw new DomainException("A course with the same title already exists.");
        }
        // Thực hiện các bước tạo Course
    }
}
```

- Ưu điểm:
- Xử lý các quy tắc kinh doanh không thuộc về domain nhưng vẫn cần đảm bảo khi thực hiện các use case cụ thể.

### 3. Validation tại Domain Layer

- Mục tiêu:
- Bảo vệ tính toàn vẹn của domain model thông qua các invariants (quy tắc bất biến).
- Đây là lớp quan trọng nhất để đảm bảo dữ liệu trong domain luôn hợp lệ bất kể nó được nhập từ đâu.
- Vị trí:
- Trong các lớp Entities/, ValueObjects/, hoặc Aggregates/ thuộc Domain/.
- Validation tại đây thường sử dụng constructor hoặc các phương thức static (e.g., create) để đảm bảo dữ liệu luôn đúng khi được khởi tạo.
- Ví dụ:

```php
class CourseTitle
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException("Course title cannot be empty.");
        }
        if (strlen($value) > 255) {
            throw new InvalidArgumentException("Course title must not exceed 255 characters.");
        }
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
```
- Ưu điểm:
- Giữ tính toàn vẹn cho domain model, đảm bảo dữ liệu luôn chính xác trong mọi trạng thái.

### Tóm tắt cách phối hợp validation

| Loại Validation                       | Vị trí                                        | Ví dụ cụ thể                                                                          |
|---------------------------------------|-----------------------------------------------|---------------------------------------------------------------------------------------|
| Input Validation                      | Presentation/Requests/                        | Xác thực các trường title, duration trong API request.                                |
| Use Case Validation                   | Application/UseCases/                         | Kiểm tra tính hợp lệ của action như Course title không được trùng.                    |
| Domain Validation (Invariants)        | Domain/Entities/ hoặc Domain/ValueObjects/    | Đảm bảo bất biến, ví dụ: Course title không được rỗng, Duration phải lớn hơn 0.       |
| Domain Validation (Business Rules)    | Domain/Services/                              | Kiểm tra các quy tắc kinh doanh phức tạp như kiểm tra prerequisites, related courses. |

### Tuân thủ DDD, Clean Architecture, CQRS

- DDD: Các quy tắc domain nên được đặt trong Domain/, đảm bảo domain model tự bảo vệ mình.
- Clean Architecture: Các lớp khác nhau (Presentation, Application, Domain) có trách nhiệm rõ ràng, không bị trùng lặp logic validation.
- CQRS:
- Command Validation: Kiểm tra dữ liệu cho các thao tác ghi (Create, Update, Delete).
- Query Validation: Kiểm tra các tham số truy vấn, đảm bảo hợp lệ khi thực hiện các thao tác đọc (Search, Filter).

### Lời khuyên

- Tránh lặp lại validation:
- Sử dụng các lớp domain (e.g., ValueObjects) để kiểm tra một lần và tái sử dụng.
- Tách riêng các quy tắc đơn giản và phức tạp:
- Các validation đơn giản (required, format) nên ở Presentation/.
- Các validation phức tạp (liên quan đến business rules) nên ở Application/ hoặc Domain/.
 
