<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('geozones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id'); // Foreign key for category
            $table->geometry('geometry', 'MULTIPOLYGON', 4326); // Store MultiPolygon in SRID 4326
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        // Create spatial index on geometry
        DB::statement('CREATE INDEX geozones_geometry_idx ON geozones USING GIST (geometry);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geozones');
    }
};
