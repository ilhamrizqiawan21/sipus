<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        if (! Schema::hasColumn('book_copy_statuses', 'code')) {
            Schema::table('book_copy_statuses', function (Blueprint $table) {
                $table->string('code', 30)->nullable()->after('id');
            });

            DB::statement(<<<'SQL'
UPDATE `book_copy_statuses`
SET `code` = CASE
    WHEN `is_available` = 1 THEN 'available'
    WHEN LOWER(`name`) LIKE '%pinjam%' THEN 'borrowed'
    WHEN LOWER(`name`) LIKE '%rusak%' THEN 'damaged'
    WHEN LOWER(`name`) LIKE '%hilang%' THEN 'lost'
    ELSE CONCAT('status-', `id`)
END
WHERE `code` IS NULL
SQL
            );
        }

        if (! Schema::hasColumn('borrowing_items', 'inventory_code_snapshot')) {
            Schema::table('borrowing_items', function (Blueprint $table) {
                $table->string('inventory_code_snapshot', 30)->nullable()->after('book_title_snapshot');
            });
        }

        if (Schema::hasColumn('book_copies', 'inventory_code')) {
            DB::statement(<<<'SQL'
UPDATE `borrowing_items` `bi`
JOIN `book_copies` `bc` ON `bc`.`id` = `bi`.`book_copy_id`
SET `bi`.`inventory_code_snapshot` = `bc`.`inventory_code`
WHERE `bi`.`inventory_code_snapshot` IS NULL
SQL
            );
        } elseif (Schema::hasColumn('book_copies', 'barcode')) {
            DB::statement(<<<'SQL'
UPDATE `borrowing_items` `bi`
JOIN `book_copies` `bc` ON `bc`.`id` = `bi`.`book_copy_id`
SET `bi`.`inventory_code_snapshot` = COALESCE(`bc`.`barcode`, CONCAT('COPY-', `bc`.`id`))
WHERE `bi`.`inventory_code_snapshot` IS NULL
SQL
            );
        } else {
            DB::statement(<<<'SQL'
UPDATE `borrowing_items`
SET `inventory_code_snapshot` = CONCAT('COPY-', `book_copy_id`)
WHERE `inventory_code_snapshot` IS NULL
SQL
            );
        }

        DB::statement('DROP VIEW IF EXISTS `v_active_borrowings`');
        DB::statement('DROP VIEW IF EXISTS `v_book_availability`');

        DB::statement(<<<'SQL'
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_active_borrowings` AS
select
  `bi`.`id` AS `borrowing_item_id`,
  `bt`.`transaction_code` AS `transaction_code`,
  `bt`.`member_name_snapshot` AS `member_name_snapshot`,
  `bt`.`member_class_snapshot` AS `member_class_snapshot`,
  `bi`.`book_title_snapshot` AS `book_title_snapshot`,
  `bi`.`inventory_code_snapshot` AS `inventory_code_snapshot`,
  `bt`.`borrow_date` AS `borrow_date`,
  `bi`.`due_date` AS `due_date`,
  `bi`.`status` AS `status`,
  (case when ((`bi`.`status` = 'borrowed') and (`bi`.`due_date` < curdate())) then 1 else 0 end) AS `is_overdue`,
  (to_days(curdate()) - to_days(`bi`.`due_date`)) AS `days_overdue`
from (`borrowing_items` `bi`
join `borrowing_transactions` `bt` on ((`bt`.`id` = `bi`.`borrowing_transaction_id`)))
where (`bi`.`status` = 'borrowed');
SQL
        );

        DB::statement(<<<'SQL'
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_book_availability` AS
select
  `b`.`id` AS `book_id`,
  `b`.`title` AS `title`,
  `b`.`isbn` AS `isbn`,
  `c`.`name` AS `category_name`,
  count(`bc`.`id`) AS `total_copies`,
  sum((case when (`bcs`.`code` = 'available') then 1 else 0 end)) AS `available_copies`,
  sum((case when (`bcs`.`code` = 'borrowed') then 1 else 0 end)) AS `borrowed_copies`
from (((`books` `b`
left join `book_copies` `bc` on (((`bc`.`book_id` = `b`.`id`) and (`bc`.`deleted_at` is null))))
left join `book_copy_statuses` `bcs` on ((`bcs`.`id` = `bc`.`status_id`)))
left join `categories` `c` on ((`c`.`id` = `b`.`category_id`)))
where (`b`.`deleted_at` is null)
group by `b`.`id`,`b`.`title`,`b`.`isbn`,`c`.`name`;
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS `v_active_borrowings`');
        DB::statement('DROP VIEW IF EXISTS `v_book_availability`');
    }
};
