Mở rộng chức năng của UpdateCoursePrerequisiteHandler

Dưới đây là các cách mở rộng UpdateCoursePrerequisiteHandler để xử lý nghiệp vụ cập nhật prerequisites (điều kiện tiên quyết) của khóa học một cách mạnh mẽ hơn:

1. Xác minh các prerequisites có tồn tại

Trước khi cập nhật, cần kiểm tra tất cả các điều kiện tiên quyết mới đều tồn tại trong hệ thống, tránh tham chiếu tới các khóa học không tồn tại.

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Bước 1: Xác minh prerequisites tồn tại
    $nonexistentPrerequisites = $this->courseWriteRepository->findNonexistentPrerequisites($command->newPrerequisites);
    if (!empty($nonexistentPrerequisites)) {
        throw new ValidationException('Các khóa học không tồn tại: ' . implode(', ', $nonexistentPrerequisites));
    }

    // Tiếp tục logic cũ
}

2. Kiểm tra vòng lặp điều kiện tiên quyết

Ngăn chặn vòng lặp trong các điều kiện tiên quyết, ví dụ: Khóa A yêu cầu Khóa B, nhưng Khóa B lại yêu cầu Khóa A.

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Bước 2: Kiểm tra vòng lặp điều kiện tiên quyết
    if ($this->validationService->hasCircularDependency($command->courseId, $command->newPrerequisites)) {
        throw new ValidationException('Phát hiện vòng lặp trong các điều kiện tiên quyết.');
    }

    // Tiếp tục logic cũ
}

3. Phát hành sự kiện miền (Domain Event)

Sau khi cập nhật, phát hành sự kiện miền CoursePrerequisitesUpdatedEvent để các hệ thống khác có thể xử lý (ví dụ: cập nhật cache, thông báo).

use App\Events\CoursePrerequisitesUpdatedEvent;

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Logic cũ...

    // Bước 3: Phát hành sự kiện miền
    $event = new CoursePrerequisitesUpdatedEvent($course->getId(), $command->newPrerequisites);
    $this->eventBus->publish($event);
}

4. Ghi log thay đổi để audit

Ghi lại thông tin ai đã thực hiện thay đổi, khi nào thay đổi, và chi tiết nội dung thay đổi để phục vụ việc kiểm tra sau này.

use App\Logging\AuditLogger;

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Logic cũ...

    // Bước 4: Ghi log
    AuditLogger::log(
        'CoursePrerequisiteUpdated',
        [
            'courseId' => $command->courseId,
            'newPrerequisites' => $command->newPrerequisites,
            'updatedBy' => auth()->user()->id, // Ví dụ: ID người dùng hiện tại
        ]
    );
}

5. Cập nhật cache

Nếu ứng dụng sử dụng cache, cần đảm bảo dữ liệu cache liên quan đến khóa học được làm mới sau khi cập nhật.

use App\Cache\CourseCache;

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Logic cũ...

    // Bước 5: Làm mới cache
    CourseCache::invalidate($command->courseId);
}

6. Gửi thông báo

Thông báo cho các bên liên quan (giảng viên, học viên, quản trị viên) về thay đổi điều kiện tiên quyết của khóa học.

use App\Services\NotificationService;

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Logic cũ...

    // Bước 6: Gửi thông báo
    NotificationService::notifyUsers(
        $course->getEnrolledUsers(),
        'Điều kiện tiên quyết của khóa học đã được cập nhật.',
        [
            'courseId' => $course->getId(),
            'prerequisites' => $command->newPrerequisites,
        ]
    );
}

7. Hỗ trợ cập nhật một phần (Partial Update)

Cho phép chỉ thêm hoặc xóa một số điều kiện tiên quyết mà không cần ghi đè toàn bộ danh sách.

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Bước 7: Hợp nhất (merge) các điều kiện tiên quyết
    $currentPrerequisites = $course->getPrerequisites();

    // Logic hợp nhất (ví dụ: thêm hoặc ghi đè dựa trên flag trong command)
    $updatedPrerequisites = array_merge($currentPrerequisites, $command->newPrerequisites);

    // Loại bỏ trùng lặp và tiếp tục
    $course->updatePrerequisites(array_unique($updatedPrerequisites));

    // Lưu khóa học
    $this->courseWriteRepository->save($course);
}

8. Rollback nếu có lỗi

Đảm bảo nếu bất kỳ bước nào trong quá trình xử lý gặp lỗi, tất cả thay đổi trước đó đều được hoàn tác (rollback).

use Illuminate\Support\Facades\DB;

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    DB::beginTransaction();

    try {
        // Logic xử lý...

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

9. Tách riêng logic kiểm tra

Tách các logic kiểm tra (validation) thành các dịch vụ riêng biệt để dễ dàng bảo trì và tái sử dụng.

public function handle(UpdateCoursePrerequisiteCommand $command): void
{
    // Validate prerequisites tồn tại
    $this->validationService->validatePrerequisitesExist($command->newPrerequisites);

    // Kiểm tra vòng lặp điều kiện tiên quyết
    $this->validationService->validateNoCircularDependencies($command->courseId, $command->newPrerequisites);

    // Tiếp tục xử lý...
}

Handler hoàn chỉnh

class UpdateCoursePrerequisiteHandler implements CommandHandlerInterface
{
    public function __construct(
        private CourseWriteRepositoryInterface $courseWriteRepository,
        private PrerequisiteValidationService $validationService,
        private EventBusInterface $eventBus,
        private NotificationService $notificationService,
        private CacheService $cacheService
    ) {}

    public function handle(UpdateCoursePrerequisiteCommand $command): void
    {
        DB::beginTransaction();

        try {
            // Xác minh prerequisites tồn tại
            $this->validationService->validatePrerequisitesExist($command->newPrerequisites);

            // Kiểm tra vòng lặp
            $this->validationService->validateNoCircularDependencies($command->courseId, $command->newPrerequisites);

            // Lấy và cập nhật khóa học
            $course = $this->courseWriteRepository->findById($command->courseId);
            if (!$course) {
                throw new NotFoundException('Không tìm thấy khóa học.');
            }

            $course->updatePrerequisites($command->newPrerequisites);
            $this->courseWriteRepository->save($course);

            // Phát hành sự kiện
            $this->eventBus->publish(new CoursePrerequisitesUpdatedEvent($course->getId(), $command->newPrerequisites));

            // Gửi thông báo
            $this->notificationService->notifyUsers(
                $course->getEnrolledUsers(),
                'Điều kiện tiên quyết đã được cập nhật.',
                ['courseId' => $course->getId()]
            );

            // Cập nhật cache
            $this->cacheService->invalidate($course->getId());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

Lợi ích

	1.	Tính linh hoạt: Các chức năng mở rộng như kiểm tra vòng lặp, log, cache, và thông báo giúp hệ thống đầy đủ hơn.
	2.	Bảo trì dễ dàng: Tách biệt các logic kiểm tra và sử dụng dịch vụ giúp dễ mở rộng hoặc sửa đổi.
	3.	Đảm bảo tính nhất quán: Rollback tránh các thay đổi không hoàn chỉnh khi gặp lỗi.
