<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('package_id');
            $table->string('package_name');
            $table->text('allowed_calling_country');
            $table->text('excluded_calling_country');
            $table->string('package_type');
            $table->string('price');
            $table->string('stripe_package_id');
            $table->string('call_minutes');
            $table->string('call_country');
            $table->string('call_country_code');
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
        Schema::dropIfExists('packages');
    }
}
