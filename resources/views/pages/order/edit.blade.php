@extends('layouts.app')

@section('title', 'Edit Order Status')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></div>
                    <div class="breadcrumb-item">All Order</div>
                </div>
            </div>
            <div class="section-body">
                <div class="card">
                    <form action="{{ route('order.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control selectric @error('status') is-invalid @enderror" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="on_delivery" {{ $order->status == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>No Resi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('shipping_resi') is-invalid @enderror" name="shipping_resi" id="shipping_resi" value="{{ $order->shipping_resi }}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-secondary" id="generateResiButton">Generate Random Resi</button>
                                    </div>
                                </div>
                                @error('shipping_resi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('generateResiButton').addEventListener('click', function() {
            const resi = 'RESI' + Math.floor(Math.random() * 1000000000);
            document.getElementById('shipping_resi').value = resi;
        });
    </script>
@endpush
