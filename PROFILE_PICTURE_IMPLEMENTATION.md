# Profile Picture Storage & Display - Implementation Guide

## ✅ Current Implementation Status

The profile picture functionality is **FULLY IMPLEMENTED** and working correctly.

---

## 📸 Profile Picture Upload Flow

### **Step 1: Upload**
**File**: `app/Http/Controllers/Web/ProfileController.php`
**Method**: `updateVolunteerProfile()`

```php
// Validation (line 77):
'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
// Accepted formats: JPEG, JPG, PNG, GIF
// Max file size: 2MB (2048 KB)
```

### **Step 2: Storage**
**File**: `app/Services/VolunteerService.php`
**Method**: `updateProfile()`

```php
// Lines 73-88:
if (isset($data['profile_picture']) && $data['profile_picture']) {
    // 1. Delete old profile picture if exists
    if ($volunteer->profile_picture) {
        Storage::disk('public')->delete($volunteer->profile_picture);
    }

    // 2. Generate unique filename
    $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

    // 3. Store in storage/app/public/profile_pictures/
    $path = $file->storeAs('profile_pictures', $filename, 'public');

    // 4. Update database
    $volunteer->update([
        'profile_picture' => $path, // e.g., "profile_pictures/profile_1_1703001234.jpg"
    ]);
}
```

**Storage Location**:
- Physical path: `storage/app/public/profile_pictures/profile_{user_id}_{timestamp}.{ext}`
- Public URL: `storage/profile_pictures/profile_{user_id}_{timestamp}.{ext}`

### **Step 3: Database Storage**
**Table**: `volunteers`
**Column**: `profile_picture` (VARCHAR)

```sql
volunteers {
    id
    user_id
    profile_picture  -- Stores path: "profile_pictures/profile_1_1703001234.jpg"
    ...
}
```

### **Step 4: Display**
**File**: `resources/views/dashboard/volunteer.blade.php`
**Lines**: 21-25

```blade
@if(auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
    <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}"
         alt="{{ auth()->user()->name }}"
         class="w-full h-full object-cover rounded-full">
@else
    <!-- Fallback: Show initials in colored circle -->
    <div class="w-full h-full flex items-center justify-center text-2xl font-bold text-indigo-600">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
@endif
```

**Display Logic**:
1. Check if volunteer has `profile_picture` in database
2. If YES: Display image from `storage/profile_pictures/`
3. If NO: Display fallback (first letter of name in colored circle)

---

## 🔄 Complete Profile Picture Workflow

```
User Uploads Picture (via Profile Edit Form)
    ↓
Controller Validates (max 2MB, jpeg/jpg/png/gif)
    ↓
Service Deletes Old Picture (if exists)
    ↓
Service Stores New Picture in storage/app/public/profile_pictures/
    ↓
Database Updated (volunteers.profile_picture = "profile_pictures/profile_X_timestamp.ext")
    ↓
User Logs Out and Logs Back In
    ↓
Dashboard Loads
    ↓
Blade Template Checks if profile_picture exists
    ↓
IF EXISTS: Display from storage/
IF NOT EXISTS: Display initials fallback
```

---

## 📁 File Structure

```
mindova/
├── storage/
│   └── app/
│       └── public/
│           └── profile_pictures/          ← Physical storage
│               ├── profile_1_1703001234.jpg
│               ├── profile_2_1703005678.png
│               └── ...
├── public/
│   └── storage/                          ← Symbolic link to storage/app/public
│       └── profile_pictures/             ← Accessible via URL
├── database/
│   └── migrations/
│       └── 2025_12_20_135459_add_profile_picture_to_volunteers_table.php
└── app/
    ├── Services/
    │   └── VolunteerService.php          ← Upload logic
    └── Http/
        └── Controllers/
            └── Web/
                └── ProfileController.php  ← Form handling
```

---

## 🔧 Setup Requirements

### 1. **Create Storage Link** (Required for public access)
```bash
php artisan storage:link
```
This creates a symbolic link from `public/storage` → `storage/app/public`

### 2. **Set Permissions** (Required for uploads)
```bash
# Windows (Command Prompt as Administrator):
icacls "storage\app\public\profile_pictures" /grant Users:(OI)(CI)F

# Linux/Mac:
chmod -R 775 storage/app/public/profile_pictures
chown -R www-data:www-data storage/app/public/profile_pictures
```

### 3. **Verify Directory Exists**
```bash
# Create directory if it doesn't exist
mkdir -p storage/app/public/profile_pictures
```

---

## 🧪 Testing Profile Picture Upload

### Test 1: Upload New Picture
```
1. Login as volunteer (e.g., alex@volunteer.com / password123)
2. Go to Profile Edit page
3. Upload a profile picture (JPEG/PNG, < 2MB)
4. Submit form
5. ✅ Picture should appear on dashboard
```

### Test 2: Replace Existing Picture
```
1. Login as volunteer who already has a profile picture
2. Upload a different picture
3. ✅ Old picture should be deleted from storage
4. ✅ New picture should appear on dashboard
```

### Test 3: Persistence After Logout
```
1. Login as volunteer
2. Upload profile picture
3. Logout
4. Login again
5. ✅ Profile picture should still be displayed
```

### Test 4: Database Verification
```sql
-- Check profile picture path in database
SELECT id, user_id, profile_picture
FROM volunteers
WHERE profile_picture IS NOT NULL;

-- Expected result:
-- profile_picture: "profile_pictures/profile_1_1703001234.jpg"
```

### Test 5: File Existence
```bash
# Check if file exists in storage
ls -la storage/app/public/profile_pictures/

# Expected output:
# profile_1_1703001234.jpg
# profile_2_1703005678.png
```

---

## 🎨 Where Profile Picture is Displayed

### 1. **Volunteer Dashboard** ✅
- **File**: `resources/views/dashboard/volunteer.blade.php`
- **Location**: Header section (top-right)
- **Size**: 64px × 64px (w-16 h-16)
- **Style**: Circular with border

### 2. **Task Details Page** ✅
- **File**: `resources/views/tasks/show.blade.php`
- **Location**: Assigned Volunteers sidebar (lines 276-284)
- **Fallback**: First letter of name in colored circle

### 3. **Challenge Team Members** ✅
- **File**: `resources/views/challenges/show.blade.php`
- **Location**: Team members section
- **Fallback**: Initials in gradient background

---

## 🚨 Common Issues & Solutions

### Issue 1: "Image not displaying after upload"
**Solution**:
```bash
# Ensure storage link exists:
php artisan storage:link

# Check permissions:
ls -la public/storage
```

### Issue 2: "404 error when accessing image"
**Solution**:
```php
// Verify path in database:
SELECT profile_picture FROM volunteers WHERE id = 1;

// Should be: "profile_pictures/profile_1_123456.jpg"
// NOT: "/storage/profile_pictures/profile_1_123456.jpg"
```

### Issue 3: "Old image not deleted"
**Solution**:
```php
// Check VolunteerService.php line 75-77
// Should have:
if ($volunteer->profile_picture) {
    Storage::disk('public')->delete($volunteer->profile_picture);
}
```

### Issue 4: "Upload fails silently"
**Solution**:
```bash
# Check storage permissions:
chmod -R 775 storage/app/public

# Check max upload size in php.ini:
upload_max_filesize = 5M
post_max_size = 5M
```

---

## 📊 Database Schema

```sql
-- Volunteers table structure
CREATE TABLE volunteers (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    field VARCHAR(255),
    profile_picture VARCHAR(255) NULL,  -- Stores path
    cv_file_path VARCHAR(255) NULL,
    reputation_score DECIMAL(5,2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample data
INSERT INTO volunteers (user_id, field, profile_picture) VALUES
(1, 'Software Engineering', 'profile_pictures/profile_1_1703001234.jpg'),
(2, 'Data Science', 'profile_pictures/profile_2_1703005678.png'),
(3, 'Chemical Engineering', NULL); -- No profile picture yet
```

---

## 🔐 Security Considerations

1. **File Type Validation**: Only allows image files (JPEG, PNG, GIF)
2. **Size Limit**: Maximum 2MB to prevent large uploads
3. **Unique Filenames**: Uses `user_id + timestamp` to prevent overwriting
4. **Path Storage**: Stores relative path, not full URL (prevents URL tampering)
5. **Old File Cleanup**: Automatically deletes old pictures on update

---

## ✅ Implementation Checklist

- [x] Database column `profile_picture` in volunteers table
- [x] Migration to add profile_picture column
- [x] Upload form validation (type, size)
- [x] File storage in `storage/app/public/profile_pictures/`
- [x] Database update with file path
- [x] Old file deletion on replacement
- [x] Display in volunteer dashboard
- [x] Fallback for missing pictures (initials)
- [x] Symbolic link `public/storage` → `storage/app/public`
- [x] Circular profile picture styling
- [x] Persistence after logout/login

---

## 📝 Example Code Snippets

### Upload Form (Profile Edit Page)
```blade
<form method="POST" action="{{ route('profile.update.volunteer') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Profile Picture
        </label>
        <input type="file"
               name="profile_picture"
               accept="image/jpeg,image/jpg,image/png,image/gif"
               class="form-input">
        <p class="text-xs text-gray-500 mt-1">
            Max size: 2MB. Formats: JPEG, PNG, GIF
        </p>
    </div>

    <button type="submit" class="btn-primary">
        Update Profile
    </button>
</form>
```

### Display Profile Picture (Any View)
```blade
@if(auth()->user()->volunteer?->profile_picture)
    <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}"
         alt="{{ auth()->user()->name }}"
         class="w-12 h-12 rounded-full object-cover">
@else
    <div class="w-12 h-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
@endif
```

---

**Status**: ✅ Fully Implemented and Working
**Last Updated**: 2025-12-20
**Version**: 1.0
