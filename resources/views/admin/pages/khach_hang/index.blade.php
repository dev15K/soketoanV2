@extends('admin.layouts.master')
@section('title')
    Khách hàng
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Khách hàng</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Khách hàng</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên khách hàng</label></h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên khách hàng">
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
                        <h5 class="card-title">Thêm mới khách hàng</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.khach.hang.store') }}" class="d-none">
                        @csrf
                        <div class="form-group">
                            <label for="ten">Họ và tên</label>
                            <input type="text" class="form-control" id="ten" name="ten" value="{{ old('ten') }}"
                                   required>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="tinh_thanh">Tỉnh thành</label>
                                <input type="text" class="form-control" id="tinh_thanh" name="tinh_thanh"
                                       value="{{ old('tinh_thanh') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                       value="{{ old('so_dien_thoai') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiKhachHang::ACTIVE() }}" {{ old('trang_thai') == \App\Enums\TrangThaiKhachHang::ACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiKhachHang::ACTIVE() }}
                                    </option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiKhachHang::INACTIVE() }}" {{ old('trang_thai') == \App\Enums\TrangThaiKhachHang::INACTIVE() ? 'selected' : '' }}>
                                        {{ \App\Enums\TrangThaiKhachHang::INACTIVE() }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dia_chi">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control" id="dia_chi" name="dia_chi"
                                   value="{{ old('dia_chi') }}">
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
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('khach_hang')">Xoá
                            tất cả
                        </button>
                    </div>
                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="20%">
                            <col width="10%">
                            <col width="x">
                            <col width="20%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" name="check_all" id="check_all">
                            </th>
                            <th scope="col">Hành động</th>
                            <th scope="col">Họ và tên</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Địa chỉ</th>
                            <th scope="col">Công nợ</th>
                            <th scope="col">Trạng thái</th>
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
                                        <a href="{{ route('admin.khach.hang.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.khach.hang.delete', $data->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>{{ $data->ten }}</td>
                                <td>{{ $data->so_dien_thoai }}</td>
                                <td>{{ $data->dia_chi }}</td>
                                <td>
                                    @php
                                        $ban_hangs = \App\Models\BanHang::where('trang_thai', '!=', \App\Enums\TrangThaiBanHang::DELETED())
                                            ->where('khach_hang_id', $data->id)
                                            ->get();

                                        $total = 0;
                                        foreach ($ban_hangs as $ban_hang) {
                                            $total += $ban_hang->cong_no;
                                        }
                                    @endphp
                                    {{ parseNumber($total, 0) }} VND
                                </td>
                                <td>{{ $data->trang_thai }}</td>
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
