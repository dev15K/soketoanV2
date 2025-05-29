@extends('admin.layouts.master')
@section('title')
    Nhà cung cấp
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Nhà cung cấp</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Nhà cung cấp</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên nhà cung cấp</label></h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên nhà cung cấp">
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
                        <h5 class="card-title">Thêm mới nhà cung cấp</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nha.cung.cap.store') }}" class="d-none">
                        @csrf
                        <div class="form-group">
                            <label for="ten">Họ và tên</label>
                            <input type="text" class="form-control" id="ten" name="ten" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="tinh_thanh">Tỉnh thành</label>
                                <input type="text" class="form-control" id="tinh_thanh" name="tinh_thanh">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::ACTIVE() }}">{{ \App\Enums\TrangThaiNhaCungCap::ACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::INACTIVE() }}">{{ \App\Enums\TrangThaiNhaCungCap::INACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNhaCungCap::BLOCKED() }}">{{ \App\Enums\TrangThaiNhaCungCap::BLOCKED() }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dia_chi">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control" id="dia_chi"
                                   name="dia_chi">
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
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('nha_cung_cap')">Xoá tất cả</button>
                    </div>
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="20%">
                            <col width="10%">
                            <col width="x">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" name="check_all" id="check_all">
                            </th>
                            <th scope="col">Họ và tên</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Địa chỉ</th>
                            <th scope="col">Công nợ</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row"><input type="checkbox" name="check_item[]" id="check_item{{ $data->id }}"
                                                       value="{{ $data->id }}"></th>
                                <td>{{ $data->ten }}</td>
                                <td>{{ $data->so_dien_thoai }}</td>
                                <td>{{ $data->dia_chi }}</td>
                                <td>
                                    @php
                                        $nguyen_lieu_thos = \App\Models\NguyenLieuTho::where('trang_thai', '!=', \App\Enums\TrangThaiNguyenLieuTho::DELETED())
                                            ->where('nha_cung_cap_id', $data->id)
                                            ->get();

                                        $total = 0;
                                        foreach ($nguyen_lieu_thos as $nguyen_lieu_tho) {
                                            $total += $nguyen_lieu_tho->cong_no;
                                        }
                                    @endphp
                                    {{ parseNumber($total, 0) }} VND
                                </td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nha.cung.cap.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nha.cung.cap.delete', $data->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
