<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwillEdgePurgeTables extends Migration
{
    public function up(): void
    {
        Schema::create('twill_edge_purge', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->boolean('hsts_enabled')->default(true);
            $table->text('hsts')->nullable();
        });

        Schema::create('twill_edge_purge_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'twill_edge_purge', 'twill_edge_purge');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twill_edge_purge_revisions');
        Schema::dropIfExists('twill_edge_purge');
    }
}
