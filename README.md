# CIT-U Lost & Found System - Maroon & Gold Edition 🦁

A vanilla PHP/MySQL web system for managing lost and found items, built with MVP principles and vertical slicing.


1. **Move Files:** Copy the `lostfound` folder to `C:\xampp\htdocs\`.
2. **Start XAMPP:** Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. **Setup Database:**
   - Go to `http://localhost/phpmyadmin`.
   - Create a new database named `lostfound_db`.
   - Import `schema.sql` (found in the root folder).
   - Import `seed.sql` (to add sample data).
4. **Run it:** Go to `http://localhost/lostfound` in your browser.

---

## 🔑 Admin Login
- **Employee ID:** `EMP001`
- **Password:** `password123`

---

## KUNG DI KA KALOGIN RENZ:

1. Locate the **`fix.php`** file in the root folder.
2. Visit `http://localhost/lostfound/fix.php` in your browser.
3. This will automatically sync the password hash with your specific PHP version.
4. Try logging in again!



## renz i run ni kung dili ka kadagan sa website sa terminal

# copy and paste in a new terminal love u
C:\xampp\php\php.exe -S localhost:8000 -t "c:/Users/Htrias/OneDrive/Desktop/Heron JDBC/Heron IM/lostfound"
