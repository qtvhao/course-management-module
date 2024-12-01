Dưới đây là hai bảng phân loại các event handlers trong kiến trúc dựa trên mức độ tuân thủ quy tắc:

Bảng 1: Event Handlers Không Tuân Thủ Quy Tắc
| Event Handler                        	| Lý Do Vi Phạm                                                                 																																				| Giải Pháp Thay Thế                                                                                       								|
|---------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------|
| VerifyPrerequisitesOnCourseCreation  	| - Thực hiện logic domain trong event handler.  <br /> - Vi phạm Separation of Concerns vì xử lý logic nên nằm ở Domain layer. 																								| - Tạo một PrerequisiteValidationService trong Domain layer và gọi từ event handler.                      								|
| EnforceCourseDurationPolicyOnUpdate  	| - Chứa logic chính sách domain (policy). <br />  - Vi phạm nguyên tắc “domain logic không nằm ở Application layer”. 																											| - Đưa logic kiểm tra vào CourseAggregate hoặc Domain Service. <br /> - Handler chỉ gọi service hoặc aggregate để kiểm tra. 			|
| NotifyAdminOnCourseDeleted           	| - Xử lý nhiều trách nhiệm cùng lúc (gửi thông báo và log). <br />  - Vi phạm nguyên tắc Single Responsibility Principle (SRP). 																								| - Chia thành hai handler riêng:  <br />1. SendAdminNotificationOnCourseDeleted.  <br />2. LogCourseDeletionToAuditTrail. 				|
| SyncCourseToSearchEngine 				| - Trực tiếp sử dụng công cụ tìm kiếm cụ thể (Elasticsearch).<br /> - Vi phạm Dependency Inversion Principle và Infrastructure Isolation. <br /> - Tạo interface CourseSearchSyncServiceInterface trong Application layer. 	| - Viết một implementation cụ thể trong Infrastructure layer (e.g., ElasticsearchCourseSearchSyncService). 							|
| EnrollDefaultStudentsOnCourseCreated 	| - Kết hợp domain rule (enroll student) trong event handler. <br />  - Vi phạm Separation of Concerns vì hành vi này thuộc domain hoặc use case. 																				| - Sử dụng một Use Case (e.g., EnrollDefaultStudentsUseCase) để thực hiện hành động này. <br /> - Handler chỉ gọi đến use case. 		|

Bảng 2: Event Handlers Tuân Thủ Quy Tắc

| Event Handler                     		| Lý Do Tuân Thủ Quy Tắc                                                                                       																								|
|-------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SendEmailOnCourseCreated          		| - Có một trách nhiệm duy nhất: gửi email thông báo. <br /> - Không chứa logic gửi email trực tiếp, mà sử dụng một service (e.g., NotificationService). 													|
| UpdateCacheOnCourseUpdated        		| - Xử lý duy nhất việc cập nhật cache. <br /> - Tương tác với hệ thống cache qua abstraction (CacheServiceInterface), không phụ thuộc trực tiếp vào implementation cụ thể. 								|
| LogCourseCreatedEvent             		| - Đảm bảo ghi log một cách độc lập, không can thiệp logic business. <br /> - Hoàn toàn phụ thuộc vào LoggerInterface, đảm bảo tính linh hoạt và khả năng mở rộng. 										|
| TriggerApprovalWorkflowOnCourseCreated 	| - Không chứa logic phức tạp của workflow. <br /> - Giao trách nhiệm khởi chạy workflow cho một WorkflowServiceInterface, đảm bảo handler chỉ làm nhiệm vụ trigger. 										|
| ClearCourseCacheOnCourseDeleted   		| - Có một trách nhiệm duy nhất là xóa dữ liệu cache liên quan. <br /> - Hoạt động như một thành phần kỹ thuật phụ thuộc abstraction (CacheServiceInterface), đảm bảo không vi phạm nguyên tắc phân tầng. 	|

### Giải Thích và Kết Luận

1. **Event Handlers Không Tuân Thủ:**
	- Các handler này vi phạm nguyên tắc kiến trúc chủ yếu do:
	  - Chứa logic domain.
	  - Phụ thuộc vào cụ thể (tight coupling).
	  - Xử lý nhiều trách nhiệm.
	- Giải pháp thay thế: sử dụng Domain Services, Use Cases, và các interface abstraction.

2. **Event Handlers Tuân Thủ:**
	- Các handler này thực hiện đúng vai trò của mình, đảm bảo:
	  - Single Responsibility: chỉ thực hiện một nhiệm vụ.
	  - Dependency Inversion: phụ thuộc vào interface thay vì implementation cụ thể.
	  - Separation of Concerns: không chứa logic thuộc về Domain layer.

Khi thiết kế hệ thống, nên tập trung xây dựng các handler nhỏ gọn, tách biệt trách nhiệm và sử dụng abstraction để tránh vi phạm các nguyên tắc kiến trúc. 🚀
