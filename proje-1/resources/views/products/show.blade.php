<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ürün Detay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">{{-- sadece veri gösteriyor. --}}
        <h2>{{ $product->name }}</h2>

        <table class="table table-bordered w-50">
            <tr>
                <th>Açıklama</th>
                <td>{{ $product->description ?? '-' }}</td>
            </tr>
            <tr>
                <th>Fiyat</th>
                <td>{{ number_format($product->price, 2) }} ₺</td>
            </tr>
            <tr>
                <th>Stok</th>
                <td>{{ $product->stock }}</td>
            </tr>
        </table>

        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Düzenle</a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Listeye Dön</a>
    </div>
</body>

</html>
