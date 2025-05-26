// Here's a plan to fix your migrations:

// 1. Rename duplicate migrations to avoid conflicts
// - Rename 2025_05_24_093002_create_students_table.php to 2025_05_24_093002_create_students_table_v2.php
// - Rename 2025_05_20_010606_create_registrars_table.php to 2025_05_20_010606_create_registrars_table_v1.php
// - Rename 2025_05_24_193626_create_registrars_table.php to 2025_05_24_193626_create_registrars_table_v2.php
// - Rename 2025_05_24_214536_create_announcements_table.php to 2025_05_24_214536_create_announcements_table_v2.php

// 2. Modify each migration to check if table exists before creating
