
# Database Schema Documentation

## Tables

### 1. `users` Table

| Column     | Type         | Description                         |
|------------|--------------|-------------------------------------|
| `id`       | INT          | Primary Key, Auto-increment         |
| `username` | VARCHAR(255) | Unique username                     |
| `password` | VARCHAR(255) | User password (hashed)              |

### 2. `posts` Table

| Column     | Type         | Description                         |
|------------|--------------|-------------------------------------|
| `id`       | INT          | Primary Key, Auto-increment         |
| `title`    | VARCHAR(255) | Title of the post                   |
| `content`  | TEXT         | Content of the post                 |
| `created_at` | TIMESTAMP  | Post creation timestamp (auto set)  |
| `user_id`  | INT          | Foreign Key (References `users.id`) |

---

## Relationships

- **One-to-Many**  
  The `posts` table has a foreign key `user_id` that references the `id` field in the `users` table.
