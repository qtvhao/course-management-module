Để thực hiện task update prerequisite của một khóa học, bạn cần phân tách rõ ràng trách nhiệm giữa các thành phần: QueueCommandBus, CommandHandler, Command, và UseCase, theo nguyên tắc CQRS và Clean Architecture. Dưới đây là cách phân tách:

1. QueueCommandBus

	•	Trách nhiệm:
	•	Đóng vai trò là trung gian để truyền và xử lý Command.
	•	Đẩy các Command vào hàng đợi (queue) để thực hiện bất đồng bộ (asynchronous).
	•	Tách biệt việc gửi Command với logic thực thi, đảm bảo tính độc lập giữa các lớp.
	•	Triển khai:

class QueueCommandBus implements CommandBusInterface
{
    public function dispatch(CommandInterface $command): void
    {
        // Đẩy command vào hàng đợi (ví dụ: sử dụng Laravel Queue)
        Queue::push($command);
    }
}

2. Command

	•	Trách nhiệm:
	•	Đóng gói dữ liệu cần thiết để thực hiện hành động (update prerequisite).
	•	Là một đối tượng bất biến, không chứa logic xử lý.
	•	Triển khai:

class UpdateCoursePrerequisiteCommand implements CommandInterface
{
    public function __construct(
        public readonly string $courseId,
        public readonly array $newPrerequisites // Danh sách prerequisite mới
    ) {}
}

3. CommandHandler

	•	Trách nhiệm:
	•	Thực thi logic cần thiết để xử lý Command.
	•	Phối hợp với các Domain Services, Repositories, và các lớp liên quan để thực hiện hành động.
	•	Không gọi trực tiếp từ trình điều khiển (Controller) mà luôn thông qua CommandBus.
	•	Triển khai:

class UpdateCoursePrerequisiteHandler implements CommandHandlerInterface
{
    public function __construct(
        private CourseWriteRepositoryInterface $courseWriteRepository,
        private PrerequisiteValidationService $validationService
    ) {}

    public function handle(UpdateCoursePrerequisiteCommand $command): void
    {
        // Lấy khóa học từ Repository
        $course = $this->courseWriteRepository->findById($command->courseId);

        if (!$course) {
            throw new NotFoundException('Course not found');
        }

        // Validate prerequisites
        $this->validationService->validate($command->newPrerequisites);

        // Update prerequisites
        $course->updatePrerequisites($command->newPrerequisites);

        // Lưu khóa học
        $this->courseWriteRepository->save($course);
    }
}

4. UseCase

	•	Trách nhiệm:
	•	Là entry point (điểm vào) chính của Application Layer để thực hiện yêu cầu cụ thể.
	•	Giao tiếp với CommandBus để gửi Command.
	•	Triển khai:

class UpdateCoursePrerequisiteUseCase
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function execute(string $courseId, array $newPrerequisites): void
    {
        // Tạo Command
        $command = new UpdateCoursePrerequisiteCommand($courseId, $newPrerequisites);

        // Gửi Command qua CommandBus
        $this->commandBus->dispatch($command);
    }
}

Luồng hoạt động

	1.	Controller nhận request từ người dùng:
	•	Gửi yêu cầu tới UpdateCoursePrerequisiteUseCase.
	•	Ví dụ:
```php
$useCase->execute('course-123', ['math101', 'cs101']);
```

	2.	UseCase tạo và đẩy Command vào CommandBus.
	3.	QueueCommandBus đẩy Command vào hàng đợi.
	4.	CommandHandler xử lý Command:
	•	Lấy dữ liệu từ repository.
	•	Thực hiện validation qua domain service.
	•	Cập nhật và lưu lại khóa học.

Kết quả

	•	Các thành phần được phân tách rõ ràng:
	•	QueueCommandBus chỉ xử lý việc đẩy và phân phối Command.
	•	Command chỉ là dữ liệu.
	•	CommandHandler thực hiện logic xử lý cụ thể.
	•	UseCase cung cấp điểm vào cho ứng dụng.
	•	Logic xử lý được tách biệt, dễ bảo trì và mở rộng.
