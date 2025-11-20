@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Loại Phụ Kiện
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Loại Phụ Kiện</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Loại Phụ Kiện</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Chỉnh sửa Loại Phụ Kiện</h5>
                    <form method="post" action="{{ route('admin.loai.phu.kien.update', $loai_phu_kien->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="ten_phu_kien">Tên Loại Phụ Kiện</label>
                            <input type="text" id="ten_phu_kien" name="ten_phu_kien" class="form-control"
                                   value="{{ old('ten_phu_kien', $loai_phu_kien->ten_phu_kien) }}" required>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_phu_kien">Mã Phụ Kiện</label>
                                <input type="text" id="ma_phu_kien" name="ma_phu_kien" class="form-control"
                                       value="{{ old('ma_phu_kien', $loai_phu_kien->ma_phu_kien) }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" id="don_vi_tinh" name="don_vi_tinh" class="form-control"
                                       value="{{ old('don_vi_tinh', $loai_phu_kien->don_vi_tinh) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ten_phu_kien">Mô tả phụ kiện</label>
                            <textarea name="mo_ta_phu_kien" id="mo_ta_phu_kien" class="form-control"
                                      rows="10">{{ $loai_phu_kien->mo_ta_phu_kien }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                    </form>

                </div>

            </div>
        </div>
    </section>
@endsection
