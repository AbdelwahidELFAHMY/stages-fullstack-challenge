<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Changement de la collation de la table articles vers utf8mb4_unicode_ci
        // qui gère correctement les accents
        DB::statement('ALTER TABLE articles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    public function down()
    {
        DB::statement('ALTER TABLE articles CONVERT TO CHARACTER SET latin1 COLLATE latin1_general_ci');
    }
};
