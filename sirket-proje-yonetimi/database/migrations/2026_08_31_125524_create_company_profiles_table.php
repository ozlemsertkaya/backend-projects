<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete(); //ş tabosundaki id ile eşelşecek yabancı anahtar sütunu açar/
            //şirket tablosunun id sutununa bağla kuralını verir/eğer ana şirket silinirse ona ait profil satırının da arkada çöp veri bırakmadan otomatik silnmesini sağlar.
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
