<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `clicks.created_at` gets a database default.
 *
 * It is NOT NULL and has no default, so every row depends on the one caller
 * that happens to pass `now()` explicitly. That is fine while there is exactly
 * one insert path — and it is a trap the day a second one appears (a backfill,
 * a console command, a raw insert during an incident): the insert either fails
 * or, in a non-strict MySQL, writes a zero date into the analytics table.
 *
 * DATA PLAN (DATABASE-STANDARD.md §11): purely a column default. No existing row
 * is read or written; the change applies to future inserts only.
 *
 * ROLLBACK: down() removes the default and leaves the column exactly as it was.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->timestamp('created_at')->change();
        });
    }
};
