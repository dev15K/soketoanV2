@extends('admin.layouts.master')
@section('title')
    Loại Phụ Kiện
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Loại Phụ Kiện</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Loại Phụ Kiện</li>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Loại Phụ Kiện</h5>
                    </div>
                    <form method="post" action="{{ route('admin.loai.quy.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_phu_kien">Tên Loại Phụ Kiện</label>
                            <input type="text" id="ten_phu_kien" name="ten_phu_kien" class="form-control"
                                   value="{{ old('ten_phu_kien') }}" required>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_phu_kien">Mã Phụ Kiện</label>
                                <input type="text" id="ma_phu_kien" name="ma_phu_kien" class="form-control"
                                       value="{{ old('ma_phu_kien') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" id="don_vi_tinh" name="don_vi_tinh" class="form-control"
                                       value="{{ old('don_vi_tinh') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ten_phu_kien">Mô tả phụ kiện</label>
                            <textarea name="mo_ta_phu_kien" id="mo_ta_phu_kien" class="form-control"
                                      rows="10"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <div class="d-flex mb-4 mt-3 justify-content-end">
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('loai_phu_kien')">Xoá
                            tất cả
                        </button>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="15%">
                                <col width="x">
                                <col width="15%">
                                <col width="15%">
                            </colgroup>
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tên phụ kiện</th>
                                <th scope="col">Mã phụ kiện</th>
                                <th scope="col">Đơn vị tính</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <th scope="row"><input type="checkbox" name="check_item[]"
                                                           id="check_item{{ $data->id }}"
                                                           value="{{ $data->id }}"></th>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.loai.quy.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.loai.quy.delete', $data->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $data->ten_phu_kien }}</td>
                                    <td>{{ $data->ten_loai_quy }}</td>
                                    <td>{{ $data->don_vi_tinh }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
