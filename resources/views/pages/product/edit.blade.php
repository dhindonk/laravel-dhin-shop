@extends('layouts.app')

@section('title', 'Edit Product')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('library/codemirror/theme/duotone-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit product</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></div>
                    <div class="breadcrumb-item">Edit product</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Products</h2>
                <div class="card">
                    <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-md-12 col-lg-8">
                                    <div class="form-group">
                                        <label>Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $product->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Price *</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            Rp.
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control currency @error('price') is-invalid @enderror"
                                                        name="price" id="price"
                                                        value="{{ old('price', $product->price) }}">
                                                    @error('price')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Stock *</label>
                                                <input type="number"
                                                    class="form-control @error('stock') is-invalid @enderror" name="stock"
                                                    value="{{ old('stock', $product->stock) }}">
                                                @error('stock')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Category *</label>
                                        <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                            name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Photo Product (Maks. Size: 2MB)</label>
                                        <div id="image-preview"
                                            class="image-preview @if (session('error')) is-invalid @endif">
                                            <label id="image-label">Choose File</label>
                                            <input type="file" name="image" id="image-upload" />
                                            <img id="image-tampil"
                                                src="{{ asset('storage/assets/products/' . $product->image) }}"
                                                class="img-fluid @error('image') is-invalid @enderror" alt=""
                                                style="height: 100% !important; object-fit: cover !important">
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-12 col-md-12">Description *</label>
                                <div class="col-12">
                                    <textarea class="summernote @error('description') is-invalid @enderror" name="description">{{ $product->description }}</textarea>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function formatRupiah(angka, prefix) {
            var numberString = angka.replace(/[^,\d]/g, '').toString(),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const priceInput = document.getElementById('price');

            // Memanggil formatRupiah() saat halaman dimuat
            priceInput.value = formatRupiah(priceInput.value);

            priceInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, ''); // Hanya angka yang diizinkan
                const formattedValue = formatRupiah(value);
                e.target.value = formattedValue;
            });

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const value = priceInput.value.replace(/\D/g,
                    ''); // Hapus semua karakter non-digit sebelum submit
                priceInput.value = value;
            });
        });
    </script>

    <script>
        document.getElementById('image-upload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-tampil').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('library/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>


    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/upload-preview/upload-preview.js') }}"></script>
    <script src="{{ asset('js/page/features-post-create.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
@endpush
