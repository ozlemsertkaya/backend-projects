<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ürün Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Ürünü Düzenle</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')//HTML sadece get ve post destekler.burada satır forma gizli bir input ekliyor
            <div class="mb-3">
                <label class="form-label">Ürün Adı</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
            </div> {{-- kullanıcı hata aldığında devreye girer yanlış bile olsa önceki değeri gösterir. --}}
            <div class="mb-3"> {{-- sayfa ilk açıldığında mevcut değer gösterilir(product->name) --}}
                <label class="form-label">Açıklama</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Fiyat</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product->price) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
            </div>
            <button type="submit" class="btn btn-warning">Güncelle</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Vazgeç</a>
        </form>
    </div>
</body>

</html>
