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
    Schema::create('urgent_payment_requests', function (Blueprint $table) {
      $table->id();
      $table->date('request_date');
      $table->string('requestor');
      $table->string('expense_no')->nullable();
      $table->string('department');
      $table->string('causing_department');
      $table->string('supplier');
      $table->decimal('amount', 10, 2);
      $table->enum('currency', ['MXN', 'USD', 'CNY'])->default('MXN');
      $table->date('payment_due_date');
      $table->text('description')->nullable();
      $table->text('justification')->nullable();
      $table->text('cause')->nullable();
      $table->text('risk')->nullable();
      $table->string('signature_path')->nullable();
      $table->string('device_token')->nullable()->index();
      $table->string('status')->default('In Review');
      $table->string('access_token', 64)->unique()->nullable();
      $table->string('reason')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('urgent_payment_requests');
  }
};
