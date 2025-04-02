# Database and SQL Techniques in LearnUP

This project utilizes the following database and SQL-related techniques:

## 1. Database Design
- **Normalization**: Ensuring the database is structured to reduce redundancy and improve data integrity.
- **Schema Design**: Creating tables, relationships, and constraints to model the application's data.

### Key Tables
- **Users Table**: Stores user information such as name, email, password, role (e.g., user, writer, admin), and profile details (e.g., bio, avatar).
- **Posts Table**: Stores blog posts with fields like title, content, status (pending, approved, rejected), image path, PDF file path, and timestamps.
- **Likes Table**: Tracks likes on posts with `user_id` and `post_id` as foreign keys.
- **Comments Table**: Stores comments and replies on posts with fields like `user_id`, `post_id`, and the comment content.
- **Notifications Table**: Manages notifications for users and admins, including fields like `user_id`, type (e.g., post_approved, post_rejected), message, and read status.
- **Logs Table**: Tracks user activities such as login, post creation, and updates for auditing purposes.

### Relationships
- **One-to-Many**: 
  - A user can create multiple posts.
  - A post can have multiple comments.
- **Many-to-Many**:
  - Users can like multiple posts, and a post can be liked by multiple users (managed via the `likes` table).
- **One-to-One**:
  - Each user has a unique profile (e.g., avatar, bio).

---

## 2. SQL Queries
- **CRUD Operations**:
    - `SELECT` for retrieving data.
    - `INSERT` for adding new records.
    - `UPDATE` for modifying existing records.
    - `DELETE` for removing records.
- **Joins**: Combining data from multiple tables using INNER JOIN, LEFT JOIN, etc.
- **Aggregations**: Using functions like `COUNT`, `SUM`, `AVG`, `MIN`, and `MAX`.
- **Pagination**: Using `LIMIT` and `OFFSET` to fetch data in chunks for better performance.
- **Subqueries**: Fetching related data, such as finding the most liked post.

---