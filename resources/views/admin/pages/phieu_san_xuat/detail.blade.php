@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Phiếu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Phiếu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Phiếu sản xuất</li>
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
                    <h5 class="card-title">Chỉnh sửa Phiếu sản xuất</h5>
                    <form method="post" action="{{ route('admin.phieu.san.xuat.update', $phieu_san_xuat) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="code">Mã Phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code" name="code"
                                       value="{{ $code }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="so_lo_san_xuat">Số LÔ SX</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="so_lo_san_xuat"
                                       name="so_lo_san_xuat" value="{{ $so_lo_san_xuat }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nguyen_lieu_id">Mã lô hàng</label>
                                <select id="nguyen_lieu_id" name="nguyen_lieu_id" class="form-control">
                                    @foreach($nltinhs as $nltinh)
                                        <option {{ $nltinh->id == $phieu_san_xuat->nguyen_lieu_id ? 'selected' : '' }}
                                                value="{{ $nltinh->id }}">{{ $nltinh->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tong_khoi_luong">Khối lượng</label>
                                <input type="text" class="form-control onlyNumber" id="tong_khoi_luong"
                                       name="tong_khoi_luong" value="{{ $phieu_san_xuat->tong_khoi_luong }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Illuminate\Support\Carbon::parse($phieu_san_xuat->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $phieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        {{ $phieu_san_xuat->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection
