<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('attachment')->nullable();
            $table->text('comment')->nullable();
            $table->datetime('closed_date')->nullable();
            $table->string('closed_by')->nullable();
            $table->bigInteger('admin_id')->unsigned()->signed()->nullable();   
            $table->integer('status')->signed()->default(0)->comment('0-Pending, 1-Success, 2-Cancel');
            $table->timestamps();
            $table->softDeletes();

            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_requests');
    }
}
