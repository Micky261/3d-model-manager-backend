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
            $table->string("name")->default("");
            $table->string("imported_name")->nullable();
            $table->text("description")->default("");
            $table->text("imported_description")->nullable();
            $table->text("notes")->default("");
            $table->json("links")->default("");
            $table->boolean("favorite")->default(false);
            $table->string("author")->default("");
            $table->string("imported_author")->nullable();
            $table->string("licence")->default("");
            $table->string("imported_licence")->nullable();
            $table->string("import_source")->nullable();
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
