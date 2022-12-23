<?php namespace Albrightlabs\ServerMonitor\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateServersTable Migration
 */
class CreateServersTable extends Migration
{
    public function up()
    {
        Schema::create('albrightlabs_servermonitor_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 255)->nullable();
            $table->text('endpoint')->nullable();
            $table->string('status', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('albrightlabs_servermonitor_servers');
    }
}
