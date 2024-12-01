Phân tách trách nhiệm cho task “Cập nhật prerequisite của một khóa học” theo CQRS, DDD và Clean Architecture

1. Tổng quan các thành phần:

	•	QueueCommandBus:
Đóng vai trò làm trung gian để gửi và xử lý các Command. Nó đảm bảo rằng các Command được xử lý không đồng bộ, và việc cập nhật hệ thống không gây gián đoạn.
	•	Command:
Là dữ liệu đơn thuần, chứa các thông tin cần thiết để thực thi task “cập nhật prerequisite”. Không chứa logic xử lý.
	•	CommandHandler:
Xử lý logic nghiệp vụ để thực hiện task dựa trên dữ liệu từ Command. Nó tương tác với domain (Aggregate Root, Repository).
	•	UseCase:
Là lớp điều phối (orchestrator), tập hợp các bước cần thiết để thực hiện yêu cầu nghiệp vụ cụ thể. Nó tạo Command, gửi tới QueueCommandBus và thực thi quy trình.

2. Cách phân tách trách nhiệm:

2.1. Command

	•	Trách nhiệm:
Đại diện cho yêu cầu “Cập nhật prerequisite của một khóa học” dưới dạng dữ liệu thuần.
	•	Ví dụ: Khóa học courseId, danh sách điều kiện tiên quyết mới prerequisites, và thông tin về người thực hiện updatedBy.
	•	Ví dụ triển khai:

namespace App\Application\Commands;

class UpdatePrerequisiteCommand
{
    public function __construct(
        public readonly string $courseId,
        public readonly array $prerequisites,
        public readonly string $updatedBy
    ) {}
}

2.2. CommandHandler

	•	Trách nhiệm:
Xử lý nghiệp vụ khi nhận được UpdatePrerequisiteCommand.
Tương tác với Aggregate Root (CourseAggregate), cập nhật trạng thái và sử dụng Repository để lưu trạng thái mới.
	•	Ví dụ triển khai:

namespace App\Application\CommandHandlers;

use App\Domain\Aggregates\CourseAggregate;
use App\Domain\Contracts\Repositories\CourseWriteRepositoryInterface;
use App\Application\Commands\UpdatePrerequisiteCommand;

class UpdatePrerequisiteHandler
{
    public function __construct(
        private CourseWriteRepositoryInterface $repository
    ) {}

    public function handle(UpdatePrerequisiteCommand $command): void
    {
        // Load the aggregate root
        $course = $this->repository->findById($command->courseId);

        // Apply the business logic
        $course->updatePrerequisites($command->prerequisites, $command->updatedBy);

        // Persist the changes
        $this->repository->save($course);
    }
}

2.3. QueueCommandBus

	•	Trách nhiệm:
Điều phối Command từ UseCase đến CommandHandler.
Nó sử dụng hàng đợi (queue) để xử lý lệnh không đồng bộ, phù hợp với CQRS khi viết và đọc được tách biệt.
	•	Ví dụ triển khai:

namespace App\Infrastructure\Messaging;

use App\Application\Contracts\CommandBusInterface;

class QueueCommandBus implements CommandBusInterface
{
    public function dispatch(object $command): void
    {
        // Dispatch the command to the queue for asynchronous handling
        // For example, using Laravel Queues:
        dispatch(function () use ($command) {
            // Resolve the handler dynamically and execute it
            $handler = $this->resolveHandler($command);
            $handler->handle($command);
        });
    }

    private function resolveHandler(object $command): object
    {
        // Logic to find the appropriate handler for the command
        $handlerClass = str_replace('Command', 'Handler', get_class($command));
        return app($handlerClass);
    }
}

2.4. UseCase

	•	Trách nhiệm:
Điều phối nghiệp vụ cấp cao.
Xác minh dữ liệu đầu vào từ người dùng, tạo Command, và gửi nó tới QueueCommandBus.
	•	Ví dụ triển khai:

namespace App\Application\UseCases;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Commands\UpdatePrerequisiteCommand;
use App\Application\DTOs\UpdatePrerequisiteDTO;

class UpdatePrerequisiteUseCase
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function execute(UpdatePrerequisiteDTO $dto): void
    {
        // Validate the input (optional)
        if (empty($dto->prerequisites)) {
            throw new \InvalidArgumentException('Prerequisites cannot be empty');
        }

        // Create the command
        $command = new UpdatePrerequisiteCommand(
            $dto->courseId,
            $dto->prerequisites,
            $dto->updatedBy
        );

        // Dispatch the command
        $this->commandBus->dispatch($command);
    }
}

2.5. Các thành phần Domain liên quan

	•	Aggregate Root (CourseAggregate):
	•	Cung cấp phương thức updatePrerequisites để cập nhật prerequisite.
	•	Ghi lại sự kiện domain (PrerequisiteUpdatedEvent).

namespace App\Domain\Aggregates;

use App\Domain\Events\PrerequisiteUpdatedEvent;

class CourseAggregate
{
    private string $id;
    private array $prerequisites = [];

    public function updatePrerequisites(array $newPrerequisites, string $updatedBy): void
    {
        // Business rule validation (e.g., no circular prerequisites)
        $this->validatePrerequisites($newPrerequisites);

        // Update the prerequisites
        $this->prerequisites = $newPrerequisites;

        // Record the domain event
        $this->recordEvent(new PrerequisiteUpdatedEvent($this->id, $newPrerequisites, $updatedBy));
    }

    private function validatePrerequisites(array $prerequisites): void
    {
        // Custom validation logic
        if (in_array($this->id, $prerequisites)) {
            throw new \DomainException('A course cannot be its own prerequisite.');
        }
    }
}

3. Quy trình phối hợp

	1.	UseCase nhận dữ liệu từ người dùng, thực hiện kiểm tra cơ bản, và tạo UpdatePrerequisiteCommand.
	2.	UseCase gửi Command tới QueueCommandBus.
	3.	QueueCommandBus đẩy Command lên hàng đợi để xử lý không đồng bộ.
	4.	CommandHandler nhận lệnh từ hàng đợi, thực thi logic nghiệp vụ qua Aggregate Root (CourseAggregate).
	5.	Repository lưu trạng thái mới vào cơ sở dữ liệu.

4. Đáp ứng nguyên tắc

	•	CQRS: Việc cập nhật trạng thái (Command) được tách biệt hoàn toàn khỏi việc truy vấn dữ liệu.
	•	DDD: Nghiệp vụ được đặt tại Aggregate Root và thể hiện qua Domain Events.
	•	Clean Architecture: Từng lớp (Domain, Application, Infrastructure) có trách nhiệm rõ ràng, không phụ thuộc chéo.
