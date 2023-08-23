<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->foreignId('type_id')
                ->references('id')->on('types')->restrictOnDelete();
        });

        Schema::create('column_document', function (Blueprint $table) {
            $table->id();
            $table->text('content');

            $table->timestamps();

            $table->foreignId('column_id')
                ->references('id')->on('columns')->restrictOnDelete();
            $table->foreignId('document_id')->constrained()
                ->references('id')->on('documents')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('column_document');
        Schema::dropIfExists('documents');
    }
};
