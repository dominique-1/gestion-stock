<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->timestamp('email_sent_at')->nullable()->after('created_at');
            $table->text('email_recipients')->nullable()->after('email_sent_at');
        });
    }

    public function down()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn(['email_sent_at', 'email_recipients']);
        });
    }
};
