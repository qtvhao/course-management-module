Khi thực hiện Test-Driven Development (TDD), thứ tự viết test cho Presentation Layer nên được xác định dựa trên nguyên tắc xây dựng từ tổng quan đến chi tiết. Dưới đây là thứ tự hợp lý để viết test cho Presentation Layer:

1. Test các Routes (Định tuyến)

	•	Mục tiêu: Đảm bảo rằng các route được định nghĩa đúng và trả về phản hồi phù hợp.
	•	Nội dung test:
	•	Route có tồn tại không? (e.g., GET /courses)
	•	HTTP method chính xác chưa? (e.g., POST, GET, PUT, DELETE)
	•	Middleware (e.g., auth, throttle) có hoạt động đúng không?
	•	Ví dụ:

public function test_course_index_route_is_accessible()
{
    $response = $this->get('/courses');
    $response->assertStatus(200);
}

2. Test Controllers

	•	Mục tiêu: Đảm bảo controller xử lý đúng logic và trả về phản hồi dự kiến.
	•	Nội dung test:
	•	Kiểm tra rằng controller gọi đúng phương thức xử lý.
	•	Kiểm tra phản hồi (response) JSON, HTML, hoặc redirect có đúng không.
	•	Đảm bảo dữ liệu trả về có cấu trúc và trạng thái phù hợp.
	•	Ví dụ:

public function test_index_returns_correct_response()
{
    $response = $this->get('/courses');
    $response->assertStatus(200);
    $response->assertViewIs('courses.index'); // Kiểm tra đúng view
}

3. Test Request Validation

	•	Mục tiêu: Đảm bảo dữ liệu gửi từ client được validate đúng trước khi xử lý.
	•	Nội dung test:
	•	Dữ liệu hợp lệ có được chấp nhận không?
	•	Dữ liệu không hợp lệ trả về lỗi cụ thể gì?
	•	Validation rules hoạt động đúng chưa?
	•	Ví dụ:

public function test_create_course_validation_fails_with_invalid_data()
{
    $response = $this->post('/courses', [
        'title' => '', // Title không được để trống
    ]);

    $response->assertStatus(422); // Unprocessable Entity
    $response->assertJsonValidationErrors(['title']);
}

4. Test Integration với Application Layer

	•	Mục tiêu: Đảm bảo Presentation Layer gọi đúng các Use Cases (hoặc Service).
	•	Nội dung test:
	•	Kiểm tra rằng controller gọi đúng Use Case/Service thông qua mock/stub.
	•	Đảm bảo rằng dữ liệu truyền từ Presentation đến Application chính xác.
	•	Ví dụ:

public function test_create_course_calls_create_use_case()
{
    $mock = $this->mock(CreateCourseUseCase::class);
    $mock->shouldReceive('execute')->once();

    $response = $this->post('/courses', [
        'title' => 'Sample Course',
    ]);

    $response->assertStatus(201);
}

5. Test giao diện người dùng (nếu áp dụng)

	•	Mục tiêu: Đảm bảo giao diện hoạt động đúng khi người dùng tương tác.
	•	Nội dung test:
	•	Giao diện có hiển thị đúng thông tin không?
	•	Các nút, form, hoặc liên kết hoạt động đúng không?
	•	Ví dụ (nếu sử dụng Laravel Dusk):

public function test_user_can_see_course_list()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/courses')
                ->assertSee('Course List'); // Xác nhận text xuất hiện
    });
}

Thứ tự tổng quan:

	1.	Route Test: Kiểm tra các endpoint có tồn tại và hoạt động đúng không.
	2.	Controller Test: Kiểm tra logic và phản hồi của các controller.
	3.	Request Validation Test: Đảm bảo dữ liệu từ client được validate đúng.
	4.	Application Layer Integration Test: Đảm bảo Presentation gọi đúng Application Layer.
	5.	UI Test (nếu có): Kiểm tra hiển thị giao diện (nếu áp dụng Dusk hoặc các công cụ tương tự).

Lưu ý quan trọng:

	•	Nguyên tắc TDD: Viết test trước khi triển khai mã nguồn cho từng lớp.
	•	Scope nhỏ: Test từng thành phần nhỏ một cách độc lập trước khi tích hợp.
	•	Mock và Stub: Sử dụng mock để đảm bảo controller chỉ kiểm tra logic mà không phụ thuộc vào Application Layer hoặc Infrastructure Layer.

Thực hiện theo thứ tự này giúp Presentation Layer được kiểm tra kỹ lưỡng và hạn chế lỗi trước khi tích hợp toàn bộ module. 🚀
