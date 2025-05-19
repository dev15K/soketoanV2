@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Sổ quỹ</li>
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
                    <h5 class="card-title">Chỉnh sửa Sổ quỹ</h5>
                    <form method="post" action="{{ route('admin.so.quy.update', $soquy->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control bg-secondary bg-opacity-10" id="ngay"
                                       name="ngay" readonly
                                       value="{{ \Illuminate\Support\Carbon::parse($soquy->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" value="{{ $soquy->ma_phieu }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="loai">Loại</label>
                                <select class="form-control" name="loai" id="loai">
                                    <option value="0">Phiếu Chi</option>
                                    <option value="1">Phiếu Thu</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="so_tien">Số tiền</label>
                                <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                                       value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="noi_dung">Nội dung</label>
                            <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
