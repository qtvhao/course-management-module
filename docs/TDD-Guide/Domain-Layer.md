Khi thực hiện Test-Driven Development (TDD) cho Domain Layer, thứ tự viết test rất quan trọng để đảm bảo bạn phát triển các tính năng theo cách incremental (từng bước) và bám sát logic nghiệp vụ. Dưới đây là thứ tự hợp lý:

1. Test Value Objects (VOs)

	•	Bắt đầu với các Value Objects vì chúng là những thành phần đơn giản, dễ kiểm tra và thường được sử dụng trong các thành phần khác của domain.
	•	Lý do:
	•	VOs là immutable và không có hành vi phức tạp.
	•	Xây dựng nền tảng cho các thành phần khác, như Entities.
	•	Ví dụ:
	•	Test các giá trị hợp lệ và không hợp lệ:

public function test_valid_course_id()
{
    $courseId = new CourseId('valid-uuid');
    $this->assertEquals('valid-uuid', $courseId->getValue());
}

public function test_invalid_course_id_throws_exception()
{
    $this->expectException(InvalidArgumentException::class);
    new CourseId('invalid-id');
}

2. Test Entities

	•	Tiếp theo, viết test cho Entities, nơi các logic nghiệp vụ phức tạp hơn có thể được kiểm tra.
	•	Lý do:
	•	Entities thường sử dụng VOs làm thuộc tính, vì vậy cần test VO trước.
	•	Đảm bảo các thuộc tính, quy tắc và hành vi trong Entities được kiểm tra kỹ càng.
	•	Ví dụ:
	•	Kiểm tra tạo Course entity:

public function test_create_course_with_valid_data()
{
    $course = new Course(
        new CourseId('valid-uuid'),
        new CourseTitle('Introduction to Programming'),
        new CourseDuration(10)
    );

    $this->assertEquals('Introduction to Programming', $course->getTitle()->getValue());
}

public function test_course_with_invalid_duration_throws_exception()
{
    $this->expectException(InvalidArgumentException::class);
    new Course(
        new CourseId('valid-uuid'),
        new CourseTitle('Math 101'),
        new CourseDuration(-5)
    );
}

3. Test Domain Services

	•	Sau khi các Entities và VOs đã được kiểm tra, hãy test Domain Services để đảm bảo các nghiệp vụ phức tạp được xử lý đúng.
	•	Lý do:
	•	Domain Services thường thực hiện các logic không thể gán cho một Entity cụ thể.
	•	Đây là nơi kết hợp nhiều Entities hoặc giá trị.
	•	Ví dụ:
	•	Kiểm tra tính hợp lệ khi thêm khóa học vào danh sách:

public function test_add_course_to_catalog()
{
    $catalog = new CourseCatalog();
    $course = new Course(
        new CourseId('valid-uuid'),
        new CourseTitle('Design Patterns'),
        new CourseDuration(15)
    );

    $catalog->addCourse($course);

    $this->assertCount(1, $catalog->getCourses());
    $this->assertEquals('Design Patterns', $catalog->getCourses()[0]->getTitle()->getValue());
}

4. Test Domain Events

	•	Sau khi kiểm tra logic nghiệp vụ, hãy kiểm tra các Domain Events.
	•	Lý do:
	•	Domain Events giúp phản ánh các thay đổi trong domain.
	•	Việc test đảm bảo rằng sự kiện được kích hoạt đúng lúc và chứa đúng dữ liệu.
	•	Ví dụ:
	•	Kiểm tra một sự kiện được tạo:

public function test_course_created_event_is_triggered()
{
    $course = new Course(
        new CourseId('valid-uuid'),
        new CourseTitle('AI Basics'),
        new CourseDuration(20)
    );

    $event = new CourseCreatedEvent($course);

    $this->assertEquals('valid-uuid', $event->getCourseId()->getValue());
    $this->assertEquals('AI Basics', $event->getCourseTitle()->getValue());
}

5. Test Aggregates

	•	Nếu domain của bạn có các Aggregates, hãy viết test cho chúng.
	•	Lý do:
	•	Aggregates là tập hợp logic từ nhiều Entities và VOs, thường xuyên tương tác với nhau.
	•	Test giúp đảm bảo các thay đổi trạng thái trong aggregate luôn hợp lệ.
	•	Ví dụ:
	•	Kiểm tra toàn bộ nghiệp vụ thêm khóa học và phát sự kiện:

public function test_add_course_to_catalog_raises_event()
{
    $catalog = new CourseCatalog();
    $course = new Course(
        new CourseId('valid-uuid'),
        new CourseTitle('Machine Learning'),
        new CourseDuration(30)
    );

    $catalog->addCourse($course);
    $events = $catalog->releaseEvents();

    $this->assertCount(1, $events);
    $this->assertInstanceOf(CourseCreatedEvent::class, $events[0]);
}

6. Test Repositories (Optional in Domain Layer)

	•	Trong TDD, repositories thường được kiểm tra ở Integration Tests hoặc trong Infrastructure Layer. Tuy nhiên, nếu cần, bạn có thể mock các interfaces để kiểm tra việc tương tác với repository.

Tổng Kết Thứ Tự:

	1.	Value Objects
	2.	Entities
	3.	Domain Services
	4.	Domain Events
	5.	Aggregates
	6.	(Optional) Repositories

Thứ tự này đảm bảo bạn kiểm tra từng phần nhỏ trước khi kiểm tra các thành phần phức tạp hơn, giúp phát hiện lỗi sớm và giữ cho logic domain luôn chính xác. 🛠️
