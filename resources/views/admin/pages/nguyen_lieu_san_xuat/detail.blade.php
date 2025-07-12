@php use App\Enums\TrangThaiNguyenLieuSanXuat; @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho Thành phẩm sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho Thành phẩm sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho Thành phẩm sản xuất</li>
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
                    <h5 class="card-title">Chỉnh sửa Kho Thành phẩm sản xuất</h5>
                    @if($nguyen_lieu_san_xuat->khoi_luong_da_dung <= 0)
                        <form method="post"
                              action="{{ route('admin.nguyen.lieu.san.xuat.update', $nguyen_lieu_san_xuat->id) }}">
                            @method('PUT')
                            @csrf
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="ngay">Ngày</label>
                                    <input type="date" class="form-control " id="ngay" name="ngay"
                                           value="{{ Carbon\Carbon::parse($nguyen_lieu_san_xuat->ngay)->format('Y-m-d') }}"
                                           required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phieu_san_xuat_id">Lô Sản Xuất</label>
                                    <select id="phieu_san_xuat_id" name="phieu_san_xuat_id"
                                            class="form-control selectCustom">
                                        @foreach($phieu_san_xuats as $phieu_san_xuat)
                                            <option
                                                {{ $phieu_san_xuat->id == $nguyen_lieu_san_xuat->phieu_san_xuat_id ? 'selected' : '' }}
                                                value="{{ $phieu_san_xuat->id }}">
                                                {{ $phieu_san_xuat->so_lo_san_xuat }}
                                                : {{ parseNumber($phieu_san_xuat->tong_khoi_luong - $phieu_san_xuat->khoi_luong_da_dung) }}
                                                kg
                                            </option>
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
                                    <input type="text" class="form-control onlyNumber" id="khoi_luong" name="khoi_luong"
                                           value="{{ $nguyen_lieu_san_xuat->khoi_luong }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tong_tien">Tổng tiền lô SX</label>
                                    <input type="text" class="form-control" id="tong_tien" name="tong_tien"
                                           value="{{ $nguyen_lieu_san_xuat->tong_tien }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="mau_sac">Màu sắc</label>
                                    <input type="text" class="form-control" id="mau_sac" name="mau_sac"
                                           value="{{ $nguyen_lieu_san_xuat->mau_sac }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="mui_thom">Mùi thơm</label>
                                    <input type="text" class="form-control" id="mui_thom" name="mui_thom"
                                           value="{{ $nguyen_lieu_san_xuat->mui_thom }}">
                                </div>
                                <div class="form-group d-none">
                                    <label for="trang_thai">Trạng thái</label>
                                    <select id="trang_thai" name="trang_thai" class="form-control">
                                        <option
                                            {{  $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::ACTIVE() ? 'selected' : '' }}
                                            value="{{ TrangThaiNguyenLieuSanXuat::ACTIVE() }}">{{ TrangThaiNguyenLieuSanXuat::ACTIVE() }}</option>
                                        <option
                                            {{ $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::INACTIVE() ? 'selected' : '' }}
                                            value="{{ TrangThaiNguyenLieuSanXuat::INACTIVE() }}">{{ TrangThaiNguyenLieuSanXuat::INACTIVE() }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nhan_vien_san_xuat">Nhân viên SX</label>
                                    <select id="nhan_vien_san_xuat" name="nhan_vien_san_xuat"
                                            class="form-control selectCustom">
                                        @foreach($nsus as $nsu)
                                            <option
                                                value="{{ $nsu->id }}" {{ $nsu->id == $nguyen_lieu_san_xuat->nhan_vien_san_xuat ? 'selected' : '' }}>
                                                {{ $nsu->full_name }}/{{ $nsu->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="bao_quan">Bảo quản</label>
                                <input type="text" class="form-control" id="bao_quan" name="bao_quan"
                                       value="{{ $nguyen_lieu_san_xuat->bao_quan }}">
                            </div>
                            <div class="form-group">
                                <label for="chi_tiet_khac">Chi tiết khác</label>
                                <textarea name="chi_tiet_khac" id="chi_tiet_khac" class="form-control" rows="5">
                                {{ $nguyen_lieu_san_xuat->chi_tiet_khac }}
                            </textarea>
                            </div>
                            @if($nguyen_lieu_san_xuat->khoi_luong_da_dung <= 0)
                                <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                        </form>
                    @endif

                </div>

            </div>
        </div>
    </section>
@endsection
