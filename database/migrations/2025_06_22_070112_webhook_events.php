<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('event_type');
            $table->json('payload');
            $table->string('source_ip')->nullable();
         
            $table->timestamp('received_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhook_events');
    }
};