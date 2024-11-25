Khi thực hiện Test-Driven Development (TDD), thứ tự viết test nên tuân theo quy trình phát triển từ trong ra ngoài để đảm bảo rằng bạn xây dựng nền tảng vững chắc trước khi triển khai các lớp bên ngoài. Điều này phù hợp với nguyên tắc Outside-In Development của TDD. Thứ tự viết test cho các lớp của ứng dụng nên như sau:

1. Domain Layer (Core Business Logic)

	•	Vì sao làm trước?
	•	Domain chứa logic nghiệp vụ quan trọng và là trung tâm của ứng dụng.
	•	Đây là lớp độc lập với các thành phần khác, nên có thể được kiểm tra mà không phụ thuộc vào cơ sở dữ liệu, API, hay UI.
	•	Loại test nên viết:
	•	Unit Test: Kiểm tra các Value Objects, Entities, Services, và Domain Events.
	•	Example:
	•	Xác thực logic của CourseId, CourseTitle.
	•	Test các hành vi của CourseDomainService.
	•	Phương pháp: Bắt đầu từ các trường hợp đơn giản nhất (happy path) trước, sau đó kiểm tra các edge cases.

2. Application Layer (Use Cases and Orchestration)

	•	Vì sao làm tiếp theo?
	•	Application layer sử dụng Domain layer để thực hiện các use case cụ thể, như CreateCourse, UpdateCourse.
	•	Test ở lớp này đảm bảo rằng các hành động nghiệp vụ được phối hợp đúng cách.
	•	Loại test nên viết:
	•	Unit Test:
	•	Kiểm tra các Use Cases như CreateCourseUseCase, UpdateCourseUseCase với mock dependencies.
	•	Test các Command Handlers và Query Handlers.
	•	Integration Test:
	•	Kiểm tra sự tương tác giữa Application và Domain layer.
	•	Test xem EventDispatcher có gửi đúng event sau khi thực hiện một use case.
	•	Example:
	•	Đảm bảo CreateCourseUseCase gọi đúng CourseWriteRepository và phát sự kiện CourseCreatedEvent.

3. Infrastructure Layer (Technical Implementations)

	•	Vì sao làm sau Application?
	•	Infrastructure phụ thuộc vào các yêu cầu từ Application và Domain layers, như cách lưu trữ dữ liệu hoặc gửi email.
	•	Infrastructure chứa logic cụ thể về cơ sở dữ liệu, API, và các công cụ kỹ thuật khác.
	•	Loại test nên viết:
	•	Integration Test:
	•	Kiểm tra việc lưu trữ vào cơ sở dữ liệu bằng EloquentCourseWriteRepository.
	•	Kiểm tra tích hợp với các dịch vụ bên ngoài, như Elasticsearch hoặc RabbitMQ.
	•	End-to-End Test (nếu cần thiết):
	•	Đảm bảo các thành phần kỹ thuật như cache, email, hoặc message queues hoạt động chính xác.
	•	Example:
	•	Kiểm tra EloquentCourseWriteRepository có lưu chính xác một Course vào cơ sở dữ liệu.

4. Presentation Layer (User-Facing Layer)

	•	Vì sao làm cuối cùng?
	•	Presentation layer phụ thuộc vào các lớp bên dưới, nên nó chỉ cần được kiểm tra sau khi các lớp Domain, Application, và Infrastructure đã sẵn sàng.
	•	Presentation chủ yếu là một “adapter” để nhận yêu cầu từ người dùng và chuyển chúng vào các use case.
	•	Loại test nên viết:
	•	Feature Test (End-to-End Test):
	•	Kiểm tra luồng hoạt động từ request đến response qua API hoặc giao diện web.
	•	Đảm bảo CourseController gọi đúng Use Case và trả về kết quả phù hợp.
	•	Example:
	•	Kiểm tra API /api/courses trả về danh sách khóa học đúng định dạng JSON.
	•	Mock Test (nếu cần):
	•	Mock các Use Case để test riêng Presentation logic.

Tóm tắt thứ tự viết test

	1.	Domain Layer → Test các logic nghiệp vụ cốt lõi trước.
	2.	Application Layer → Test các use case và sự phối hợp giữa các lớp.
	3.	Infrastructure Layer → Test tích hợp với cơ sở dữ liệu, dịch vụ bên ngoài.
	4.	Presentation Layer → Test API hoặc giao diện người dùng.

Lưu ý quan trọng khi TDD

	•	Bắt đầu từ fail test, sau đó triển khai code để test pass.
	•	Tập trung vào Unit Test và Integration Test ở các bước đầu tiên để giảm phụ thuộc.
	•	Refactor code thường xuyên để đảm bảo tính sạch và dễ đọc. 😊
