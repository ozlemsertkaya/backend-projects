<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Ürün Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Ürün Listesi</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Yeni Ürün</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ürün Adı</th>
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th style="width: 160px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                        <td>{{ number_format($product->price, 2) }} ₺</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Düzenle</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Henüz ürün eklenmemiş.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</body>

</html>
