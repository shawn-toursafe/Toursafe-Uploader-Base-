<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('file_name')->nullable();
            $table->string('archive_id')->nullable();
            $table->string('archive_url')->nullable();
            $table->string('file_ext')->nullable();
            $table->decimal('size', 25, 2)->nullable();
            $table->string('archive_type')->comment('GLACIER|DEEP_ARCHIVE')->nullable();
            $table->string('file_type')->comment('zip|document|media|other')->nullable();
            $table->string('subscription')->comment('free|paid');
            $table->boolean('downloadable')->default(0)->nullable();
            $table->boolean('download_requested')->default(0)->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
};
