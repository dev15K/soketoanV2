@extends('admin.layouts.master')
@section('title')
    Xem Kho phụ kiện
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Xem Kho phụ kiện</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Xem Kho phụ kiện</li>
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
                    <h5 class="card-title">Kho phụ kiện</h5>

                    <table class="table table-bordered">
                        <colgroup>
                            <col width="20%">
                            <col width="x">
                        </colgroup>
                        <tbody>
                        <tr>
                            <th scope="row">Ngày tạo</th>
                            <td>{{ $kho->created_at }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tên phụ kiện</th>
                            <td>{{ $kho->phukien->ten_phu_kien }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tên sản phẩm</th>
                            <td>{{ $kho->sanpham->ten_san_pham }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Mã phụ kiện</th>
                            <td>{{ $kho->phukien->ma_phu_kien }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Đơn vị tính</th>
                            <td>{{ $kho->phukien->don_vi_tinh }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Số lượng tồn kho</th>
                            <td>{{ number_format($kho->so_luong) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Số lượng đã bán</th>
                            <td>{{ number_format($kho->so_luong_da_ban) }}</td>
                        </tr>

                        </tbody>
                    </table>

                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Lịch sử nhập/bán</h5>

                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Thêm lịch sử nhập/bán
                            </button>
                        </div>

                        <table class="table table-striped">
                            <colgroup>
                                <col width="50px">
                                <col width="200px">
                                <col width="10%">
                                <col width="x">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ngày nhập/bán</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Nội dung</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lichSuPhuKien as $item)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ number_format($item->so_luong) }}</td>
                                    <td>{{ $item->ghi_chu }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <form action="{{ route('admin.lich.su.phu.kien.delete', $item->id) }}"
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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.lich.su.phu.kien.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm lịch sử nhập/bán</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="kho_phu_kien_id" id="kho_phu_kien_id" value="{{ $kho->id }}">

                        <div class="form-group col-md-12">
                            <label for="created_at">Ngày nhập</label>
                            <input type="date" class="form-control" id="created_at"
                                   name="created_at" value="{{ old('created_at', \Carbon\Carbon::now()) }}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="so_luong">Số lượng nhập</label>
                            <input type="text" class="form-control" id="so_luong"
                                   name="so_luong" value="{{ old('so_luong') }}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="ghi_chu">ghi chú</label>
                            <textarea class="form-control" name="ghi_chu" id="ghi_chu" rows="10"
                                      required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
                ¬
            </form>
        </div>
    </div>
@endsection
