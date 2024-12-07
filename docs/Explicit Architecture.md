### **What is Explicit Architecture?**

It is a method to organize and connect different parts of a software system. The author focuses on making code **clear, maintainable, and modular**, and applies this approach in real-world production systems, such as e-commerce platforms and marketplaces.

* * * * *

### **Core Concepts**

1.  **Fundamental Blocks of a System**:

    -   **User Interface**: Where users interact with the application.
    -   **Application Core (Business Logic)**: The brain of the system where the main operations happen.
    -   **Infrastructure Code**: Connects the application to external tools like databases or APIs.
2.  **Ports & Adapters**:

    -   **Ports**: Define how the application interacts with external systems, using **interfaces**.
    -   **Adapters**: Implement these interfaces to connect specific tools or UIs to the application core.
3.  **Primary (Driving) Adapters**:

    -   These adapters **tell the application what to do** (e.g., controllers in a web app).
    -   Example: A controller gets a request from the user and triggers business logic.
4.  **Secondary (Driven) Adapters**:

    -   These adapters **are told what to do by the application** (e.g., saving data to a database).
    -   Example: An adapter for MySQL implements a persistence interface for storing data.
5.  **Inversion of Control**:

    -   Dependencies flow **toward the core**, meaning the core depends on abstractions (interfaces), not concrete tools.

* * * * *

### **Organizing the Application Core**

The **Application Core** follows **Domain-Driven Design (DDD)** principles:

1.  **Application Layer**:

    -   Contains **use cases** (specific actions the app performs).
    -   Use cases depend on **repositories** and **domain objects**.
2.  **Domain Layer**:

    -   Holds the **Domain Model**, including:
        -   **Entities**: Core business objects (e.g., User, Product).
        -   **Value Objects**: Small, immutable objects with value-based equality (e.g., Money).
        -   **Domain Services**: Encapsulate complex domain logic that spans multiple entities.

* * * * *

### **Decoupling and Components**

1.  **Decoupling Components**:

    -   Components (e.g., Billing, Authentication) should not directly depend on each other. Use:
        -   **Events**: To notify other components of changes.
        -   **Shared Kernel**: A minimal shared library for common logic.
2.  **Data Management**:

    -   **Shared Storage**: A component queries data from another but doesn't modify it.
    -   **Segregated Storage**: Each component manages its own data, updating copies via events.

* * * * *

### **Flow of Control**

1.  **Without a Command/Query Bus**:

    -   The **controller** directly calls an **application service** to handle a use case.
    -   Services use **repositories** to interact with the database and trigger domain logic.
2.  **With a Command/Query Bus**:

    -   The **controller** sends a **Command** or **Query** to a Bus, which forwards it to the right **handler**.
    -   The handler manages the logic, sometimes delegating to application services.

* * * * *

### **Key Takeaways**

-   **Loose Coupling & High Cohesion**: Separate concerns so that components are independent and focused.
-   **Adaptability**: The architecture can easily adapt to changes, like switching databases or adding new UIs.
-   **Balance Complexity**: Choose the level of abstraction based on project needs, time constraints, and team expertise.
