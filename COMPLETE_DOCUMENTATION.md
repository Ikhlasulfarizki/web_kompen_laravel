# Dosen Task Management System - Complete Documentation

## Project Overview
Sistem manajemen task untuk dosen yang memungkinkan pembuatan task, manajemen peserta, dan tracking penyelesaian dengan interface yang user-friendly.

## Phase Completion Status

### ✅ Phase 1: CRUD Task Management (COMPLETE)
- Complete task creation, read, update, delete
- Participant management (accept/reject/status)
- Cascade delete (delete task → remove all participants)
- Hours stored as minutes with formatted display
- Full test coverage (10 unit tests)

### ✅ Phase 2: Dashboard & Input Restructuring (COMPLETE)
- Landing page dashboard with 3-section grid
- Statistics cards (total tasks, pending, completed)
- Upcoming tasks grid
- Pending participants queue
- Completion history table
- Split hour input (jam + menit)
- Proper conversion logic and validation

## Technology Stack
- **Framework:** Laravel 12.0
- **PHP Version:** ^8.2
- **Database:** MySQL/MariaDB with Eloquent ORM
- **Frontend:** Tailwind CSS 3.x
- **Icons:** Font Awesome 6.4.0
- **Testing:** PHPUnit via Laravel test framework

## Directory Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Dosen/
│           ├── TaskController.php (310 lines)
│           └── DashboardController.php (85 lines)
├── Models/
│   ├── Task.php (with cascade delete)
│   ├── Participant.php
│   ├── Dosen.php
│   └── ...
├── Helpers/
│   └── FormatHelper.php (formatJam function)

resources/views/
└── dosen/
    ├── dashboard.blade.php (NEW - Phase 2)
    └── tasks/
        ├── index.blade.php
        ├── create.blade.php (UPDATED - Phase 2)
        ├── edit.blade.php (UPDATED - Phase 2)
        └── show.blade.php

routes/
└── web.php (10 dosen routes)
```

## API Routes (Dosen Module)

### Dashboard
- `GET /dosen/dashboard` → DashboardController@index

### Task Management (Resource)
- `GET /dosen/tasks` → TaskController@index (list all tasks)
- `GET /dosen/tasks/create` → TaskController@create (show create form)
- `POST /dosen/tasks` → TaskController@store (save new task)
- `GET /dosen/tasks/{task}` → TaskController@show (view task details)
- `GET /dosen/tasks/{task}/edit` → TaskController@edit (show edit form)
- `PUT /dosen/tasks/{task}` → TaskController@update (save changes)
- `DELETE /dosen/tasks/{task}` → TaskController@destroy (delete task)

### Participant Management
- `POST /dosen/participants/{participant}/accept` → TaskController@acceptParticipant
- `POST /dosen/participants/{participant}/reject` → TaskController@rejectParticipant
- `PUT /dosen/participants/{participant}/status` → TaskController@updateParticipantStatus

## Key Features

### 1. Task Management
- **Create Task** with:
  - Title, Description, Location, Date/Time
  - Duration (hours + minutes separate inputs)
  - Participant quota
  - Automatic owner assignment (current dosen)

- **View Tasks** with:
  - List of all tasks with pagination
  - Quick status overview
  - Participant count vs quota
  - Action buttons (view, edit, delete)

- **Edit Task**
  - Pre-filled form with current data
  - Duration pre-extracted to jam/menit format
  - Saves new duration as minutes
  - Ownership verification

- **Delete Task** (Cascade)
  - Removes task
  - Automatically removes all associated participants
  - Referential integrity maintained

### 2. Participant Management
- **Accept Participant**
  - Verifies participant is pending (status_acc = null)
  - Sets status_acc = "Diterima"
  - Records acceptance timestamp

- **Reject Participant**
  - Removes participant from task
  - Deletes from participants table

- **Update Completion Status**
  - Changes status_penyelesaian
  - Options: "Selesai" or "Belum Selesai"
  - Timestamps completion date

### 3. Dashboard
- **Statistics**
  - Total tasks created by dosen
  - Pending participants waiting for approval
  - Completed tasks count

- **Upcoming Tasks Grid**
  - Shows next 5 tasks by date
  - Displays capacity info (filled/quota)
  - Quick access to task details

- **Pending Participants**
  - Latest 5 waiting for approval
  - Inline accept/reject buttons
  - Shows mahasiswa and class info

- **Completion History**
  - Table of last 5 completed participants
  - Shows duration in formatted time
  - Full timestamp of completion

### 4. Data Display Formatting
**Hours Formatting Function:** `formatJam($minutes)`
- Input: 90 → Output: "1 jam 30 menit"
- Input: 45 → Output: "45 menit"
- Input: 120 → Output: "2 jam"
- Input: 0 → Output: "0 menit"

## Data Models

### Task Model
```php
- id (PK)
- judul (string)
- deskripsi (nullable)
- lokasi (string)
- tanggal_waktu (datetime)
- jam_mulai (time)
- jam_selesai (time)
- jmlh_jam (integer) // in minutes
- kuota (integer)
- id_dosen (FK)
- created_at, updated_at

Relationships:
- belongsTo(Dosen)
- hasMany(Participant) - cascading delete
```

### Participant Model
```php
- id (PK)
- id_task (FK)
- id_mahasiswa (FK)
- status_acc (nullable) // "Diterima", "Ditolak", or null
- status_penyelesaian (string) // "Selesai", "Belum Selesai"
- created_at, updated_at

Relationships:
- belongsTo(Task)
- belongsTo(Mahasiswa)
```

### Dosen Model
```php
- id (PK)
- user_id (FK)
- id_prodi (FK)
- nama (string)
- created_at, updated_at

Relationships:
- belongsTo(User)
- belongsTo(Prodi)
- hasMany(Task)
```

## Input Validation

### Create/Update Task
```
judul        : required|string|max:255
deskripsi    : nullable|string
lokasi       : required|string|max:255
tanggal_waktu: required|date
kuota        : required|integer|min:1
jam_mulai    : required|date_format:H:i
jam_selesai  : required|date_format:H:i
jam          : required|integer|min:0|max:24
menit        : nullable|integer|min:0|max:59
```

### Duration Conversion
```php
jmlh_jam = (jam * 60) + menit

Examples:
- jam=2, menit=30 → 150 minutes
- jam=1, menit=0 → 60 minutes
- jam=0, menit=45 → 45 minutes
```

## Security Features

### Authentication
- All routes protected by `middleware('auth')`
- Role-based access: `middleware('role:2')` for dosen only

### Authorization
- Ownership verification in every controller method
- Dosen can only access their own tasks
- Dosen can only manage their own participants
- Prevents unauthorized access/modification

### Input Validation
- Server-side validation on all inputs
- Hour constraints (0-24 hours, 0-59 minutes)
- Date format validation
- Positive integer checks for quota

## Testing

### Unit Tests Included
**File:** `tests/Feature/DosenTaskTest.php` (280 lines)

**Test Cases (10 total):**
1. Can create task
2. Can view task list
3. Can update task
4. Can delete task (cascade)
5. Can accept participant
6. Can reject participant
7. Can update participant status
8. Validates required fields
9. Prevents unauthorized access
10. Formats hours correctly

**Run Tests:**
```bash
php artisan test tests/Feature/DosenTaskTest.php
```

## UI Components

### Tailwind Styling
- Responsive grid layouts
- Gradient backgrounds for statistics
- Hover effects on interactive elements
- Mobile-friendly navigation
- Consistent color scheme

### Forms
- Styled input fields with focus states
- Error message display
- Helper text for user guidance
- Submit buttons with visual feedback

### Tables
- Responsive horizontal scroll on mobile
- Hover row highlighting
- Pagination support
- Status badges with colors

## Usage Guide

### For Dosen

#### 1. Access Dashboard
```
Navigate to /dosen/dashboard
See overview: tasks, pending approvals, completion history
```

#### 2. Create New Task
```
Click "Buat Task Baru"
Fill form:
  - Title, Description, Location
  - Date, Start Time, End Time
  - Duration: Separate Jam and Menit fields
  - Participant quota
Submit form
System converts jam+menit to minutes and saves
```

#### 3. View Task Details
```
Click task in list
See all participants
See their acceptance status
See completion status
```

#### 4. Manage Participants
```
In task detail, see participants table
Click Accept button → participant status changes
Click Reject button → participant removed
Click Status button → change completion status (Selesai/Belum Selesai)
```

#### 5. Edit Task
```
Click Edit button
Update fields as needed
Duration shown in jam/menit format
Submit changes
System auto-converts to minutes
```

#### 6. Delete Task
```
Click Delete button
Confirm deletion
Task deleted
All participants automatically removed
```

## Maintenance & Monitoring

### Database
- Task table stores duration in minutes
- Participant table tracks status changes
- Timestamps on all records for audit trail

### Logs
- All actions logged via Laravel logging
- Check `storage/logs/` for detailed logs

### Performance
- Eager loading used to prevent N+1 queries
- Pagination on large lists
- Proper indexing on foreign keys

## Known Limitations (Phase 2 - "untuk saat ini")

Features marked for future implementation:
1. Search functionality
2. Filter by date/status
3. Export to Excel/PDF
4. Bulk participant approval
5. Task templates
6. Notification system
7. Task analytics
8. Recurring tasks
9. Email notifications
10. Calendar view

## Troubleshooting

### Dashboard not loading
- Check if dosen record exists for current user
- Verify role_id = 2 in users table
- Check database connection

### Form validation failing
- Ensure jam is 0-24, menit is 0-59
- Check date format (Y-m-d)
- Verify time format (H:i)

### Participants not appearing
- Check if participants were added by mahasiswa
- Verify status_acc is null for pending
- Check task date hasn't passed

## File Changelog

### Phase 1 Files Created
- `app/Http/Controllers/Dosen/TaskController.php`
- `app/Helpers/FormatHelper.php`
- `resources/views/dosen/tasks/index.blade.php`
- `resources/views/dosen/tasks/create.blade.php`
- `resources/views/dosen/tasks/edit.blade.php`
- `resources/views/dosen/tasks/show.blade.php`
- `tests/Feature/DosenTaskTest.php`
- Documentation files (8 markdown files)

### Phase 1 Files Modified
- `app/Models/Task.php` (cascade delete)
- `composer.json` (autoload FormatHelper)
- `routes/web.php` (dosen routes)
- `resources/views/layouts/app.blade.php` (menu)

### Phase 2 Files Created
- `app/Http/Controllers/Dosen/DashboardController.php`
- `resources/views/dosen/dashboard.blade.php`
- `PHASE_2_IMPLEMENTATION.md`

### Phase 2 Files Modified
- `resources/views/dosen/tasks/create.blade.php`
- `resources/views/dosen/tasks/edit.blade.php`
- `app/Http/Controllers/Dosen/TaskController.php`
- `routes/web.php`

## Success Metrics

✅ Phase 1 Complete:
- 100% of CRUD functionality working
- All 10 unit tests passing
- Cascade delete verified
- Helper function formatting correctly

✅ Phase 2 Complete:
- Dashboard displaying all 3 sections
- Input forms showing jam/menit separately
- Duration conversion working correctly
- Pre-fill functionality in edit form
- All routes properly configured

## Support & Contact

For questions or issues:
1. Check documentation files
2. Review test cases for examples
3. Check database schema matches models
4. Verify role_id = 2 for dosen user

---

**Status:** Production Ready 🚀
**Last Updated:** Phase 2 Complete
**Version:** 2.0.0
