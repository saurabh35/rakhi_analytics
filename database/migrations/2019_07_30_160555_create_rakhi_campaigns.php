<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRakhiCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rakhi_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("mobile");
            $table->string("rakhi");
            $table->text("sandesh");
            $table->text("otp");
            $table->text("utm_source")->nullable();
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
        Schema::dropIfExists('rakhi_campaigns');
    }
}
