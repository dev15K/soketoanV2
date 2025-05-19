@extends('admin.layouts.master')
@section('title')
    Sổ quỹ
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Sổ quỹ</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Sổ quỹ</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label></h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="date" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo ngày">
                            <div class="input-group-prepend">
                                <button type="button" class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới sổ quỹ</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.so.quy.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu"
                                       value="{{ $ma_phieu }}" required>
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
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="12%">
                            <col width="10%">
                            <col width="30%">
                            <col width="x">
                            <col width="8%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Loại</th>
                            <th scope="col">Số tiền</th>
                            <th scope="col">Nội dung</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>
                                    @if($data->loai == 0)
                                        Phiếu Chi
                                    @else
                                        Phiếu Thu
                                    @endif
                                </td>
                                <td>{{ parseNumber($data->so_tien) }} VND</td>
                                <td>{{ $data->noi_dung }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.so.quy.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="#"
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#"
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $datas->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </section>
@endsection
