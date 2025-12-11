<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceBreakChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_break_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_change_request_id');
            $table->foreign('attendance_change_request_id', 'fk_break_change')->references('id')->on('attendance_change_requests')->onDelete('cascade');
            $table->integer('break_number');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_break_change_requests');
    }
}
