<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ürün Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- DataTable
    kütüphanesinin kendi CSS dosyası.Arama kutusu, sayfalama butonları gibi elemanların görünümünü sağlıyor.Bootstrap e
    ek olarak. --}}
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Ürün Listesi</h2>
            <button class="btn btn-primary" onclick="openCreateModal()">+ Yeni Ürün</button>
        </div>

        <div id="alertBox" class="alert alert-success d-none"></div>

        {{-- Tabloyu DataTable a çevir demek için bu id kullanıyoruz. --}}
        <table id="productsTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ürün Adı</th> {{-- forelse ile satırları yazdırmsk yerine başlıkları yazıyoruz datatable kütüph.
                    kendisi js tarafında oluşturup ekleyecek. --}}
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Yeni Ürün</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="formErrors" class="alert alert-danger d-none"></div>
                    <input type="hidden" id="productId">
                    <div class="mb-3">
                        <label class="form-label">Ürün Adı</label>
                        <input type="text" id="name" class="form-control"> {{-- JS bu id lerden değer okuyup
                        gösterir. --}}
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea id="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fiyat</label>
                        <input type="number" step="0.01" id="price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" id="stock" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-success" onclick="saveProduct()">Kaydet</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').content;
        let table;
        let editMode = false;
        //sayfa yüklnince çalışan kod
        document.addEventListener('DOMContentLoaded',
            function() { //tarayıcının HTML sayfası tamamen yüklendi, artık JS çalıştırabilirsin dediği an.
                table = new DataTable('#productsTable', { //ID'li tabloyu al, DataTable'a dönüştür.
                    ajax: {
                        url: "{{ route('products.data') }}", //veriyi bu URL'den çek, gelen JSON'un içindeki data anahtarını kullan.
                        dataSrc: 'data'
                    },
                    columns: [{ //her sütunun, veri içinde hangi alana karşılık geldiğini belirtiyoruz
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'price',
                            render: (d) => parseFloat(d).toFixed(2) +
                                ' ₺' //veriyi göstermeden önce biçimlendiriyor
                        },
                        {
                            data: 'stock'
                        },
                        {
                            data: null,
                            render: (row) => `
                    <button class="btn btn-sm btn-warning" onclick="openEditModal(${row.id})">Düzenle</button> 
                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${row.id})">Sil</button>
                `
                        }
                    ]
                });
            });

        function openCreateModal() {
            editMode = false;
            document.getElementById('modalTitle').innerText = 'Yeni Ürün';
            document.getElementById('productId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value =
                ''; //tüm input kutularını boşaltıyoruz.Önceki denemeden kalan veri varsa temizlesin.
            document.getElementById('price').value = '';
            document.getElementById('stock').value = '';
            document.getElementById('formErrors').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('productModal')).show(); //modalı ekranda görünür yapıyor.
        }

        function openEditModal(id) {
            editMode = true;
            document.getElementById('formErrors').classList.add('d-none');

            fetch(`/products/${id}`, { //istek gönderildi ,cevap gelmedi ,gelince haber vericem.
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json()) //cevap geldiğinde çalışacak kısım
                .then(result => { //modal içindeki input kutularını dolduruyoruz.
                    const data = result.data;
                    document.getElementById('modalTitle').innerText = 'Ürünü Düzenle';
                    document.getElementById('productId').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('description').value = data.description ?? '';
                    document.getElementById('price').value = data.price;
                    document.getElementById('stock').value = data.stock;
                    new bootstrap.Modal(document.getElementById('productModal')).show();
                });
        }

        function saveProduct() {
            const id = document.getElementById('productId').value;

            const payload = { //input kutularındaki güncel değerleri okuyp bir obje içerisinde topluyoruz.
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                stock: document.getElementById('stock').value,
            };

            const url = editMode ? `/products/${id}` : `/products`;
            const method = editMode ? 'PUT' : 'POST'; //hileye gerek yok gerçek put gönderiyoruz

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload) //objeyi alıp metin formatına dönüştürür
                })
                .then(async response => {
                    const result = await response.json(); //await:işlemin bitmesini bekle bitmeden alt satıra geçme.

                    if (!response.ok) { //isteğin başarılı olup olmadığını gösteren bir özellik.200-299 arası true
                        showFormErrors(result.errors);
                        return;
                    }

                    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                    showAlert(result.message); //Her şey okeyse modalı kapatıp başarı mesajı veiyoruz.
                    table.ajax
                        .reload(); //DataTable a verini yeniden çek diyoruz, böylece sayfa hiç yenilenmeden tablo güncel hale geliyor.
                });
        }

        function deleteProduct(id) {
            if (!confirm('Bu ürünü silmek istediğinize emin misiniz?')) return;

            fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(response => response.json())
                .then(result => {
                    showAlert(result.message);
                    table.ajax.reload();
                });
        }

        function showFormErrors(errors) { //validate() başarısız olduğunda döndürdüğü hata objesi.
            const box = document.getElementById('formErrors');
            box.innerHTML = Object.values(errors).flat().map(e => `<div>${e}</div>`).join('');
            box.classList.remove('d-none');
        }

        function showAlert(message) { //mesajı göster 3 saniye sonra otomatk olrk tekrar gizle
            const box = document.getElementById('alertBox');
            box.innerText = message;
            box.classList.remove('d-none');
            setTimeout(() => box.classList.add('d-none'), 3000);
        }
    </script>
</body>

</html>
