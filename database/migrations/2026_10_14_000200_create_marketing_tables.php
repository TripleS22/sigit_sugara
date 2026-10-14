<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('organization')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp', 30);
            $table->string('service_type', 50);
            $table->string('budget', 50)->nullable();
            $table->text('message');
            $table->string('package_slug')->nullable();
            $table->string('source_page')->nullable();
            $table->string('referrer')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->boolean('is_qualified')->default(false);
            $table->unsignedBigInteger('deal_value')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('consent_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('conversion_events', function (Blueprint $table) {
            $table->id();
            $table->string('event', 40)->index();
            $table->string('label', 190)->nullable();
            $table->string('page_path')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['redirects', 'settings', 'conversion_events', 'leads'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
