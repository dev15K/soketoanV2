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
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="ten_san_pham" name="ten_san_pham"
                                   value="{{ $nguyenLieuThanhPham->ten_san_pham }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ngay">Ngày nhập kho</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ngay_san_xuat">Ngày sản xuất</label>
                                <input type="date" class="form-control" id="ngay_san_xuat" name="ngay_san_xuat"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->ngay_san_xuat)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="han_su_dung">Hạn sử dụng</label>
                                <input type="date" class="form-control" id="han_su_dung" name="han_su_dung"
                                       value="{{ \Carbon\Carbon::parse($nguyenLieuThanhPham->han_su_dung)->format('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="type">Loại nguyên liệu</label>
                                <select name="type" id="type" class="form-control">
                                    <option
                                        {{ $nguyenLieuThanhPham->type == 'san_xuat' ? 'selected' : '' }} value="san_xuat">
                                        Nguyên liệu Sản xuất
                                    </option>
                                    <option {{ $nguyenLieuThanhPham->type == 'tho' ? 'selected' : '' }} value="tho">
                                        Nguyên liệu Thô
                                    </option>
                                    <option
                                        {{ $nguyenLieuThanhPham->type == 'phan_loai' ? 'selected' : '' }} value="phan_loai">
                                        Nguyên liệu Phân loại
                                    </option>
                                    <option {{ $nguyenLieuThanhPham->type == 'tinh' ? 'selected' : '' }} value="tinh">
                                        Nguyên liệu Tinh
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_id">Tên nguyên liệu</label>
                                <select name="nguyen_lieu_id" id="nguyen_lieu_id" class="form-control">
                                    @foreach($nlsanxuats as $nlsanxuat)
                                        <option
                                            {{ $nlsanxuat->id == $nguyenLieuThanhPham->nguyen_lieu_id ? 'selected' : '' }}
                                            value="{{ $nlsanxuat->id }}">{{ $nlsanxuat->ten_nguyen_lieu }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="khoi_luong">Khối lượng(kg)</label>
                                <input type="number" min="0" class="form-control" id="khoi_luong" name="khoi_luong"
                                       value="{{ $nguyenLieuThanhPham->khoi_luong }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="don_vi_tinh" name="don_vi_tinh"
                                       value="{{ $nguyenLieuThanhPham->don_vi_tinh }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="so_luong">Số lượng</label>
                                <input type="text" class="form-control" id="so_luong" name="so_luong"
                                       value="{{ $nguyenLieuThanhPham->so_luong }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="price">Giá xuất kho thương mại</label>
                                <input type="number" class="form-control" id="price" name="price"
                                       value="{{ $nguyenLieuThanhPham->price }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total_price">Tổng giá</label>
                                <input type="number" class="form-control" id="total_price"
                                       value="{{ $nguyenLieuThanhPham->total_price }}" name="total_price" required>
                            </div>
                            <div class="form-group col-md-4">
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
