@extends('admin.layouts.master')
@section('title')
    Kho Phụ Kiện
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho Phụ Kiện</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Kho Phụ Kiện</li>
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
                        <h5 class="card-title">Thêm mới Kho Phụ Kiện</h5>
                    </div>
                    <form method="post" action="{{ route('admin.kho.phu.kien.store') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="loai_phu_kien_id">Lọai phụ kiện</label>
                                <select name="loai_phu_kien_id" id="loai_phu_kien_id"
                                        class="form-control selectCustom">
                                    @foreach($loaiPhuKiens as $loaiPhuKien)
                                        <option value="{{ $loaiPhuKien->id }}"
                                                {{ old('loai_phu_kien_id') == $loaiPhuKien->id ? 'selected' : '' }}>
                                            {{ $loaiPhuKien->ten_phu_kien }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="san_pham_id">Sản phẩm</label>
                                <select name="san_pham_id" id="san_pham_id"
                                        class="form-control selectCustom">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                                {{ old('san_pham_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->ten_san_pham }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="so_luong">Số lượng nhập</label>
                            <input type="text" class="form-control" id="so_luong"
                                   name="so_luong" value="{{ old('so_luong') }}" required>
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
                        <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete('kho_phu_kien')">Xoá
                            tất cả
                        </button>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table datatable_wrapper table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                                <col width="15%">
                                <col width="x">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" name="check_all" id="check_all">
                                </th>
                                <th scope="col">Hành động</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tên phụ kiện</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Mã phụ kiện</th>
                                <th scope="col">Đơn vị tính</th>
                                <th scope="col">Số lượng tồn kho</th>
                                <th scope="col">Số lượng đã bán</th>
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
                                            <a href="{{ route('admin.kho.phu.kien.detail', $data->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.kho.phu.kien.delete', $data->id) }}"
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
                                    <td>{{ $data->phukien->ten_phu_kien }}</td>
                                    <td>{{ $data->sanpham->ten_san_pham }}</td>
                                    <td>{{ $data->phukien->ma_phu_kien }}</td>
                                    <td>{{ $data->phukien->don_vi_tinh }}</td>
                                    <td>{{ number_format($data->so_luong) }}</td>
                                    <td>{{ number_format($data->so_luong_da_ban) }}</td>
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
