<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //$fillable:Listedeki yazan alanları doldurur ve kaydeder.Yani kullanıcıdan gelen her şeyi veritabanına kaydedebilirdik.Bunu kötüye kullanmayı engellemiş olduk.
    protected $fillable = ['name', 'description', 'price', 'stock'];
    //protected:sınıf özelliklerine erişimi belirler.
}
