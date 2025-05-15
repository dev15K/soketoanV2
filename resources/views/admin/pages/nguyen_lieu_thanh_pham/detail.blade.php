@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu Thô
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu Thô</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu Thô</li>
            </ol>
        </nav>
    </div>
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <section class="section">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu Thô</h5>
                    <form method="post"
                          action="{{ route('admin.nguyen.lieu.thanh.pham.update', $nguyenLieuThanhPham->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ngay">Ngày nhập kho</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="nguyen_lieu_san_xuat_id">Lô SX</label>
                                <select name="nguyen_lieu_san_xuat_id" id="nguyennguyen_lieu_san_xuat_id_lieu_id"
                                        class="form-control">
                                    @foreach($nlsanxuats as $nlsanxuat)
                                        <option {{ $nlsanxuat->id == $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id ? 'selected' : '' }}
                                            value="{{ $nlsanxuat->id }}">
                                            {{ $nlsanxuat->PhieuSanXuat->so_lo_san_xuat }}
                                            - {{ $nlsanxuat->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="san_pham_id">Mã sản phẩm</label>
                                <select name="san_pham_id" id="san_pham_id" class="form-control">
                                    @foreach($products as $product)
                                        <option
                                            {{ $product->id == $nguyenLieuThanhPham->san_pham_id ? 'selected' : '' }}
                                            value="{{ $product->id }}">
                                            {{ $product->ma_san_pham}} - {{ $product->ten_san_pham }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="so_luong">Số lượng (cái/hộp)</label>
                                <input type="text" class="form-control" id="so_luong" name="so_luong"
                                       value="{{ $nguyenLieuThanhPham->so_luong }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="price">Giá xuất kho thương mại</label>
                                <input type="number" class="form-control" id="price" name="price"
                                       value="{{ $nguyenLieuThanhPham->price }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total_price">Tổng tiền</label>
                                <input type="number" class="form-control" id="total_price"
                                       value="{{ $nguyenLieuThanhPham->total_price }}" name="total_price" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyenLieuThanhPham->trang_thai == \App\Enums\TrangThaiNguyenLieuThanhPham::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuThanhPham::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuThanhPham::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyenLieuThanhPham->trang_thai == \App\Enums\TrangThaiNguyenLieuThanhPham::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuThanhPham::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuThanhPham::INACTIVE() }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ngay_san_xuat">Ngày nhập kho</label>
                                <input type="date" class="form-control" id="ngay_san_xuat" name="ngay_san_xuat"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay_san_xuat)->format('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                      rows="5">{{ $nguyenLieuThanhPham->ghi_chu }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
