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
    {   //Schema veritabanında işlem yapmamızı sağlar.
        Schema::create('products', function (Blueprint $table) {
            $table->id(); //id nin otomatik arttığı bir sütun oluşturtuk.
            $table->string('name'); //Ürün adını yazabileceğimiz kısa metin sütunu " "
            $table->text('description')->nullable(); //Ürün için açıklama yapacağımız uzun metin sütunu " ".Buradaki nullable boş bırakabileceğimizi gösterir.
            $table->decimal('price', 10, 2); //price adında ondalık sütun " ".
            $table->integer('stock')->default(0); //stok durumunu takip edeceğimiz sütun.Tam sayı olduğu için(integer).Eğer değer yoksa 0 olur.(default(0))
            $table->timestamps(); //created_at ve updated_at olmak üzere iki sütun ekler ki kaydın yapıldığını ve ne zmn güncellendiğini tutsun.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products'); //Burada silme işlemi yapıyoruz.up fonksiyonu kurmuştu migration mimarisini.Migrate rollback çalıştırıldığında down fonk. devreye girer.Tabloyu siler.(yanlış bir şey yaptığımızda sütunlarda bunu kullanırız.)
    }
};
