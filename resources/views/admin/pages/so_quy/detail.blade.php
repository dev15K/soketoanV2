@extends('admin.layouts.master')
@section('title')
    Xem Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Xem Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Xem Sổ quỹ</li>
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
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="ngay">Ngày</label>
                            <input type="date" class="form-control" id="ngay" name="ngay"
                                   value="{{ \Illuminate\Support\Carbon::parse($soquy->ngay)->format('Y-m-d') }}"
                                   required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="ma_phieu">Mã phiếu</label>
                            <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                   name="ma_phieu" value="{{ $soquy->ma_phieu }}" readonly required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="loai">Loại phiếu</label>
                            <select class="form-control" name="loai" id="loai">
                                <option {{ $soquy->loai == 0 ? 'selected' : '' }} value="0">Phiếu Chi</option>
                                <option {{ $soquy->loai == 1 ? 'selected' : '' }} value="1">Phiếu Thu</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="loai_quy_id">Tên quỹ</label>
                            <select class="form-control" name="loai_quy_id" id="loai_quy_id">
                                @foreach($loai_quies as $loai_quy)
                                    <option {{ $loai_quy->id == $soquy->loai_quy_id ? 'selected' : '' }}
                                            value="{{ $loai_quy->id }}">{{ $loai_quy->ten_loai_quy }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="so_tien">Số tiền</label>
                            <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                                   value="{{ $soquy->so_tien }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="noi_dung">Nội dung</label>
                        <textarea name="noi_dung" id="noi_dung" class="form-control"
                                  rows="5">{{ $soquy->noi_dung }}</textarea>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
