-- ============================================
-- Reset Team Tables Script
-- Use this script when you need to drop and recreate team-related tables
-- ============================================

-- Disable foreign key checks to avoid constraint errors
SET FOREIGN_KEY_CHECKS = 0;

-- Drop child tables first (tables that reference teams)
DROP TABLE IF EXISTS team_messages;
DROP TABLE IF EXISTS team_members;

-- Drop parent table
DROP TABLE IF EXISTS teams;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Clean up migration records
DELETE FROM migrations WHERE migration IN (
    '2025_12_18_115246_create_teams_table',
    '2025_12_18_115253_create_team_members_table'
);

-- Show confirmation
SELECT 'Team tables dropped successfully. Now run: php artisan migrate' AS message;
