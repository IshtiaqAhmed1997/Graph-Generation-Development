# 🧑‍💻 Contributing Guidelines – Graph Generation + AI Project

To maintain clean, consistent, and scalable code across the project, please follow these standards:

---

## 🔤 Naming Conventions

| Item            | Style        | Example                  |
|-----------------|--------------|--------------------------|
| Classes         | PascalCase   | GoalResultService.php    |
| Variables       | camelCase    | $uploadId, $goalData     |
| DB Table Names  | snake_case   | goal_results             |
| DB Columns      | snake_case   | created_at, client_id    |
| Route Names     | kebab-case   | /goal-results/view       |

---
    
## 🧱 Folder Structure

- `app/Models` → All Eloquent models
- `app/Http/Controllers` → All controller logic
- `app/Services` → Analytics, charting, file parsing logic
- `app/Jobs` → Queue jobs like `ProcessRawRecords`
- `resources/views` → Blade UI templates

---

## 🎯 Code Style Rules (PSR-12 via Pint)

- 4 spaces for indentation (no tabs)
- Braces and spacing: `if ($x) {`
- Each class in its own file
- Avoid inline HTML or logic inside controllers

To auto-format code using Laravel Pint, run:

```bash
php artisan pint
