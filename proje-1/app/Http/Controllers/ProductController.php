<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        return view('products.index');
    }             //dosyasını bul ve göster.  //değişkeni o Blade dosyasına gönder.

    public function data()
    {
        return response()->json([
            'data' => Product::latest()->get() //DataTable kütüph. veriyi data adlı bir anahtarın içinde bekliyor.
        ]);
    }

    public function store(Request $request) //tarayıcı formdaki tüm verileri sunucuya gönderir veriler  $request adlı bir nesnenin içinde paketlenir.
    {
        $validated = $request->validate([ //gelen veriyi kontrol eden kısım.
            'name' => 'required|string|max:255', //boş bırakılmaz/metin olmalı/max 255
            //Neden max 255 meselesine gelirsek;eski MySQL geleneğiymiş.1 byte ile sayılabilecek max değer 2^8-1=255.
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product = Product::create($validated); //veritabanına yeni satır olarak kaydeder doğrulanmış veriyi.

        return response()->json([
            'success' => true,
            'message' => 'Ürün başarıyla eklendi.', //redirect() yapmıyoruz artık çünkü js bu json ceavbı okuyup ekranı kendisi güncelliyor.
            'data' => $product
        ], 201);
    }


    public function show(Product $product)
    {
        return response()->json(['data' => $product]);
    }

    public function update(Request $request, Product $product) //request yeni veriyi,product ise hangi ürünün güncelleneceğini tutar.
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Ürün güncellendi.', //redirect() yapmıyoruz artık çünkü js bu json ceavbı okuyup ekranı kendisi güncelliyor.
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete(); //ürünü veritabanından siliyor.

        return response()->json([
            'success' => true,
            'message' => 'Ürün silindi.', //redirect() yapmıyoruz artık çünkü js bu json ceavbı okuyup ekranı kendisi güncelliyor.

        ]);
    }
}
