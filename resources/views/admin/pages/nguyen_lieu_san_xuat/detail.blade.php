@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu Thô</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu sản xuất</li>
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
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu sản xuất</h5>
                    <form method="post"
                          action="{{ route('admin.nguyen.lieu.san.xuat.update', $nguyen_lieu_san_xuat->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ Carbon\Carbon::parse($nguyen_lieu_san_xuat->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phieu_san_xuat_id">Lô Sản Xuất</label>
                                <select id="phieu_san_xuat_id" name="phieu_san_xuat_id" class="form-control">
                                    @foreach($phieu_san_xuats as $phieu_san_xuat)
                                        <option
                                            {{ $phieu_san_xuat->id == $nguyen_lieu_san_xuat->phieu_san_xuat_id ? 'selected' : '' }}
                                            value="{{ $phieu_san_xuat->id }}">{{ $phieu_san_xuat->so_lo_san_xuat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
                                   value="{{ $nguyen_lieu_san_xuat->ten_nguyen_lieu }}" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="khoi_luong">Khối lượng(kg)</label>
                                <input type="number" min="0" class="form-control" id="khoi_luong" name="khoi_luong"
                                       value="{{ $nguyen_lieu_san_xuat->khoi_luong }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="don_vi_tinh" name="don_vi_tinh"
                                       value="{{ $nguyen_lieu_san_xuat->don_vi_tinh }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mau_sac">Màu sắc</label>
                                <input type="text" class="form-control" id="mau_sac" name="mau_sac"
                                       value="{{ $nguyen_lieu_san_xuat->mau_sac }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mui_thom">Mùi thơm</label>
                                <input type="text" class="form-control" id="mui_thom" name="mui_thom"
                                       value="{{ $nguyen_lieu_san_xuat->mui_thom }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{  $nguyen_lieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuSanXuat::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuSanXuat::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuSanXuat::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyen_lieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuSanXuat::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuSanXuat::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuSanXuat::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chi_tiet_khac">Chi tiết khác</label>
                            <textarea name="chi_tiet_khac" id="chi_tiet_khac" class="form-control" rows="5">
                                {{ $nguyen_lieu_san_xuat->chi_tiet_khac }}
                            </textarea>
                        </div>
                        <div class="form-group ">
                            <label for="bao_quan">Bảo quản</label>
                            <input type="text" class="form-control" id="bao_quan" name="bao_quan"
                                   value="{{ $nguyen_lieu_san_xuat->bao_quan }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
