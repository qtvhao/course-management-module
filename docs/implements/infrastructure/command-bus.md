Command handlers trong kiến trúc CQRS thường được sử dụng bởi một Command Bus. Vị trí của Command Bus trong kiến trúc phụ thuộc vào việc tuân thủ các nguyên tắc của Clean Architecture và CQRS, đặc biệt là nguyên tắc Separation of Concerns và Dependency Rule.

Command Bus Nằm Ở Layer Nào?

Command Bus thường được đặt trong Application Layer vì:
	1.	Application Layer là nơi xử lý các use case và logic điều phối giữa các tầng khác (Domain, Infrastructure).
	2.	Command Bus chịu trách nhiệm phân phối commands đến các command handlers, đây là một phần của logic điều phối ứng dụng, không liên quan đến logic kinh doanh cụ thể trong Domain Layer.

Kiến Trúc Clean Architecture và Command Bus

Dependency Rule

	•	Domain Layer không được phụ thuộc vào bất kỳ tầng nào khác.
	•	Command Bus thuộc về Application Layer và không xâm phạm vào logic kinh doanh hoặc phụ thuộc vào chi tiết kỹ thuật (Infrastructure).
	•	Application Layer phụ thuộc vào abstractions, không phụ thuộc vào implementation cụ thể của Command Bus (ví dụ, sử dụng interface cho Command Bus).

Separation of Concerns

	•	Command Handlers nằm trong Application Layer để thực thi logic điều phối cho một command cụ thể.
	•	Command Bus chỉ chịu trách nhiệm kết nối commands với command handlers, không thực hiện bất kỳ logic nghiệp vụ nào.

Luồng Hoạt Động của Command Bus

	1.	Presentation Layer (API, CLI, UI) nhận một yêu cầu từ người dùng.
	2.	Presentation gửi Command đến Application Layer thông qua Command Bus.
	3.	Command Bus:
	•	Tìm kiếm Command Handler tương ứng dựa trên loại của Command.
	•	Gửi Command đến Command Handler.
	4.	Command Handler thực thi Command thông qua các Use Case.

Ví Dụ Triển Khai Command Bus

Interface for Command Bus
```php
namespace Qtvhao\CourseManagement\Application\Contracts;

interface CommandBusInterface
{
    public function dispatch(object $command): void;
}
```

Simple Command Bus Implementation

```php
namespace Qtvhao\CourseManagement\Infrastructure\Messaging;

use Qtvhao\CourseManagement\Application\Contracts\CommandBusInterface;

class SimpleCommandBus implements CommandBusInterface
{
    private array $handlers = [];

    public function registerHandler(string $commandClass, callable $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function dispatch(object $command): void
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new \RuntimeException("No handler registered for command: $commandClass");
        }

        $handler = $this->handlers[$commandClass];
        $handler($command);
    }
}
```

Tích Hợp Command Bus với Application Layer

Sử dụng Command Bus
```php
use Qtvhao\CourseManagement\Application\Commands\CreateCourseCommand;
use Qtvhao\CourseManagement\Application\CommandHandlers\CreateCourseHandler;
use Qtvhao\CourseManagement\Application\Contracts\CommandBusInterface;
use Qtvhao\CourseManagement\Infrastructure\Messaging\SimpleCommandBus;

// Command handler
$createCourseHandler = new CreateCourseHandler($createCourseUseCase); // $createCourseUseCase được inject

// Command bus
$commandBus = new SimpleCommandBus();
$commandBus->registerHandler(CreateCourseCommand::class, [$createCourseHandler, 'handle']);

// Dispatch command
$command = new CreateCourseCommand(
    title: 'Advanced PHP',
    duration: 120,
    prerequisites: [1, 2]
);
$commandBus->dispatch($command);
```

Command Bus Tương Tác Ở Layer Nào?

Layer	Role of Command Bus
Presentation Layer	Gửi command đến Command Bus, nhưng không thực hiện bất kỳ logic nào của Command Bus.
Application Layer	Command Bus nằm ở đây và điều phối commands đến các command handlers, là một phần của use case logic.
Domain Layer	Không liên quan đến Command Bus. Logic kinh doanh chỉ nằm trong các Aggregates, Services, và Entities.
Infrastructure Layer	Có thể chứa implementation của Command Bus (như SimpleCommandBus, RabbitMQ, Kafka, v.v.).

Tóm Tắt

	•	Command Bus nằm ở Application Layer, đóng vai trò điều phối command đến command handlers.
	•	Các Command Handlers cũng nằm ở Application Layer, sử dụng Use Cases để thực thi logic nghiệp vụ.
	•	Domain Layer không bao giờ liên quan đến Command Bus, đảm bảo nguyên tắc tách biệt trách nhiệm.

Lợi Ích

	1.	Tuân thủ Clean Architecture: Logic nghiệp vụ không bị trộn lẫn với logic điều phối.
	2.	Dễ mở rộng: Có thể dễ dàng thêm các handlers mới bằng cách đăng ký chúng với Command Bus.
	3.	Decoupled Components: Presentation Layer chỉ cần giao tiếp với Command Bus, không cần biết chi tiết của handlers hoặc use cases.

🚀 Command Bus là trung tâm điều phối tại Application Layer, giúp duy trì sự sạch sẽ và tính mô-đun của kiến trúc!
