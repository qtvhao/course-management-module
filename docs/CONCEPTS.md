### **Hiểu Các Concept Kiến Trúc Trong Module Structure**

* * * * *

### **1\. Ports and Adapters Architecture (Kiến Trúc Cổng và Bộ Chuyển Đổi)**

**Ports và Adapters** là một trong những nguyên tắc cốt lõi của kiến trúc được sử dụng trong module structure. Mục tiêu của nó là **tách biệt logic cốt lõi của ứng dụng** khỏi các công cụ bên ngoài (như cơ sở dữ liệu, hệ thống messaging).

#### **Cách áp dụng trong module structure:**

-   **Ports (Cổng)**:

    -   Các **interface** trong thư mục `Contracts` định nghĩa cách mà ứng dụng core tương tác với các hệ thống bên ngoài.
    -   Ví dụ:
        -   `CourseWriteRepositoryInterface` định nghĩa cách lưu trữ dữ liệu mà không phụ thuộc vào hệ thống cơ sở dữ liệu cụ thể.
        -   `CommandBusInterface` và `EventBusInterface` định nghĩa cách xử lý command và event.
-   **Adapters (Bộ chuyển đổi)**:

    -   Các lớp trong thư mục `Infrastructure` hiện thực hóa các **Ports**, giúp kết nối ứng dụng với các công cụ cụ thể.
    -   Ví dụ:
        -   `EloquentCourseWriteRepository` sử dụng Eloquent để lưu trữ dữ liệu.
        -   `RabbitMQCommandBus` dùng RabbitMQ để gửi và nhận command/event.

#### **Lợi ích:**

-   Dễ dàng thay đổi công cụ bên ngoài mà không ảnh hưởng đến core logic.
-   Tăng khả năng tái sử dụng và mở rộng.

* * * * *

### **2\. Domain-Driven Design (DDD) -- Thiết Kế Hướng Miền**

**Domain-Driven Design** giúp tổ chức và xây dựng logic nghiệp vụ theo cách phản ánh chính xác các khái niệm trong miền kinh doanh (business domain).

#### **Cách áp dụng trong module structure:**

-   **Entities (Thực thể)**:

    -   Các đối tượng cốt lõi của miền, đại diện cho dữ liệu và logic chính.
    -   Ví dụ: `Course.php` đại diện cho khóa học và chứa các quy tắc nghiệp vụ liên quan.
-   **Value Objects (Đối tượng giá trị)**:

    -   Các thuộc tính bất biến, thể hiện ý nghĩa cụ thể.
    -   Ví dụ: `CourseId.php`, `CourseTitle.php` đảm bảo rằng ID và tên khóa học là hợp lệ.
-   **Domain Events (Sự kiện miền)**:

    -   Các sự kiện phản ánh thay đổi trong miền kinh doanh.
    -   Ví dụ: `CourseCreatedEvent` được kích hoạt khi một khóa học mới được tạo.
-   **Domain Services (Dịch vụ miền)**:

    -   Logic nghiệp vụ phức tạp không thuộc về một thực thể nào.
    -   Ví dụ: `ScheduleValidationService` kiểm tra lịch trình của khóa học.

#### **Lợi ích:**

-   Logic nghiệp vụ được cô lập và dễ hiểu.
-   Hệ thống phản ánh chính xác thực tế kinh doanh.

* * * * *

### **3\. Command-Query Responsibility Segregation (CQRS)**

CQRS chia logic hệ thống thành hai phần riêng biệt:

-   **Command**: Xử lý các hành động (write operations).
-   **Query**: Xử lý các truy vấn dữ liệu (read operations).

#### **Cách áp dụng trong module structure:**

-   **Commands và Handlers**:

    -   `CreateCourseCommand`, `UpdateCourseCommand` đại diện cho các hành động cụ thể.
    -   `CreateCourseHandler`, `UpdateCourseHandler` xử lý các command này.
-   **Queries và Handlers**:

    -   `GetCourseByIdQuery`, `SearchCoursesQuery` đại diện cho các yêu cầu truy vấn dữ liệu.
    -   `GetCourseByIdHandler`, `SearchCoursesHandler` xử lý các yêu cầu này.

#### **Lợi ích:**

-   Tăng tính tách biệt, giúp dễ dàng tối ưu hóa từng phần (ví dụ: caching cho query).
-   Tăng tính rõ ràng khi chia logic thành các use case cụ thể.

* * * * *

### **4\. Event-Driven Architecture (EDA) -- Kiến Trúc Hướng Sự Kiện**

EDA cho phép hệ thống phản ứng với các thay đổi thông qua các **domain events**.

#### **Cách áp dụng trong module structure:**

-   **Event Bus**:
    -   `EventDispatcher` đóng vai trò trung gian, gửi event đến các handler phù hợp.
-   **Event Handlers**:
    -   `SendEmailOnCourseCreated` gửi email khi khóa học mới được tạo.
    -   `UpdateCacheOnCourseUpdated` cập nhật cache khi thông tin khóa học thay đổi.

#### **Lợi ích:**

-   Loosely coupled (ít phụ thuộc), các thành phần có thể hoạt động độc lập.
-   Dễ dàng mở rộng tính năng mới bằng cách thêm event handler.

* * * * *

### **5\. Layered Architecture (Kiến Trúc Phân Lớp)**

Hệ thống được chia thành các lớp để tăng tính tổ chức và giảm sự phụ thuộc lẫn nhau.

#### **Cách áp dụng trong module structure:**

-   **Presentation Layer**:
    -   `Controllers` và `Requests` xử lý tương tác của người dùng.
-   **Application Layer**:
    -   `Commands`, `Queries`, và các handler tương ứng xử lý logic ứng dụng.
-   **Domain Layer**:
    -   Chứa logic nghiệp vụ cốt lõi.
-   **Infrastructure Layer**:
    -   Kết nối với công cụ bên ngoài như database và message queue.

#### **Lợi ích:**

-   Rõ ràng, dễ bảo trì khi mỗi lớp có trách nhiệm riêng biệt.
-   Tăng khả năng mở rộng bằng cách bổ sung hoặc thay thế các lớp.

* * * * *

### **6\. Componentization (Tách Thành Phần)**

Hệ thống được tổ chức theo **module** hoặc **bounded context**, giúp phân chia code theo từng lĩnh vực kinh doanh cụ thể.

#### **Cách áp dụng trong module structure:**

-   Module như `course-management-module` chỉ xử lý logic liên quan đến quản lý khóa học.
-   Các thành phần dùng chung (shared module) được trừu tượng hóa để dùng trong nhiều module khác.

#### **Lợi ích:**

-   Tăng tính module hóa, dễ bảo trì và nâng cấp từng phần.
-   Dễ dàng thêm tính năng mới mà không ảnh hưởng đến toàn bộ hệ thống.

* * * * *

### **Kết Luận**

Module structure được thiết kế dựa trên những concept kiến trúc hiện đại như **Ports & Adapters**, **DDD**, **CQRS**, và **EDA**, mang lại:

-   **Tính linh hoạt**: Dễ dàng thay đổi công nghệ hoặc thêm tính năng mới.
-   **Tính bảo trì**: Rõ ràng, tách biệt trách nhiệm.
-   **Tính mở rộng**: Thêm module mới mà không ảnh hưởng đến các phần khác.

Những nguyên tắc này không chỉ giúp tạo nên một hệ thống mạnh mẽ mà còn làm cho code dễ hiểu, dễ phát triển trong dài hạn.
