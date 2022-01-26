<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_packages', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->boolean('has_paid');
            $table->string('allowed_minutes');
            $table->string('country_code');
            $table->string('remaining_minutes');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('customer_packages');
    }
}
