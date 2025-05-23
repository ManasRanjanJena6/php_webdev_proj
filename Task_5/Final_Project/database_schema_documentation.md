# Database Schema Documentation

## Tables

### 1. `users` Table

| Column     | Type         | Description                         |
|------------|--------------|-------------------------------------|
| `id`       | INT          | Primary Key, Auto-increment         |
| `username` | VARCHAR(255) | Unique username                     |
| `password` | VARCHAR(255) | User password (hashed)              |
| `role`     | ENUM('admin', 'editor', 'user') | User role for access control |

---

### 2. `posts` Table

| Column      | Type         | Description                         |
|-------------|--------------|-------------------------------------|
| `id`        | INT          | Primary Key, Auto-increment         |
| `title`     | VARCHAR(255) | Title of the post                   |
| `content`   | TEXT         | Content of the post                 |
| `created_at`| TIMESTAMP    | Post creation timestamp (auto set)  |
| `user_id`   | INT          | Foreign Key (References `users.id`) |

---

### 3. `settings` Table

| Column             | Type         | Description                |
|--------------------|--------------|----------------------------|
| `id`               | INT          | Primary Key, Auto-increment|
| `site_name`        | VARCHAR(255) | Name of the website        |
| `site_description` | TEXT         | Description of the website |

---

## Relationships

### 1. `users` → `posts`
- **One-to-Many**  
  The `posts` table has a foreign key `user_id` that references the `id` field in the `users` table.
- A single user can create multiple posts.

---

## Example SQL Queries

### Create `users` Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'user') NOT NULL
);
```

### Create `posts` Table
```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Create `settings` Table
```sql
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL,
    site_description TEXT
);
```

---

## Future Enhancements

- Add `comments` table for post comments.
- Add `categories` table for post categorization.
- Add `audit_logs` table for tracking user actions.

---

Let me know if you need further assistance!