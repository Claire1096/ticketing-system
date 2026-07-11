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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');
            $table->enum('category', ['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others']);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Open', 'In Progress', 'Pending', 'Resolved', 'Closed'])->default('Open');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('technician_remarks')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
