<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreeDModelTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("models", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string("name");
            $table->string("imported_name")->default("null");
            $table->longText("description");
            $table->longText("imported_description")->default("null");
            $table->longText("notes");
            $table->json("links");
            $table->boolean("favorite");
            $table->string("author");
            $table->string("imported_author")->default("null");
            $table->string("licence");
            $table->string("imported_licence")->default("null");
            $table->string("import_source")->default("null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("models");
    }
}
