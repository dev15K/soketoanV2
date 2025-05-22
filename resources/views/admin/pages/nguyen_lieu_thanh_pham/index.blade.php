@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu Thành phẩm
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu Thành phẩm</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu Thành phẩm</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm</label>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-4 w-100">
                            <div class="col-md-4 form-group">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <label for="ngay">Ngày: </label>
                                    <input type="date" class="form-control" id="ngay_search"
                                           value="{{ $ngay_search }}" name="ngay">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <select name="nguyen_lieu_san_xuat_id_search" id="nguyen_lieu_san_xuat_id_search"
                                        class="form-control">
                                    <option value="">Lựa chọn Lô SX</option>
                                    @foreach($nlsanxuats as $nlsanxuat)
                                        <option
                                            {{ $nlsanxuat->id == $nguyen_lieu_san_xuat_id_search ? 'selected' : '' }}
                                            value="{{ $nlsanxuat->id }}">
                                            {{ $nlsanxuat->PhieuSanXuat->so_lo_san_xuat }}
                                            - {{ $nlsanxuat->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select name="san_pham_id_search" id="san_pham_id_search" class="form-control">
                                    <option value="">Lựa chọn Mã sản phẩm</option>
                                    @foreach($products as $product)
                                        <option {{ $product->id == $san_pham_id_search ? 'selected' : '' }}
                                                value="{{ $product->id }}">
                                            {{ $product->ma_san_pham}} - {{ $product->ten_san_pham }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-primary" onclick="searchTable()" type="button">Tìm kiếm</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <script>
            function searchTable() {
                const ngay_search = $('#ngay_search').val();
                const nguyen_lieu_san_xuat_id_search = $('#nguyen_lieu_san_xuat_id_search').val();
                const san_pham_id_search = $('#san_pham_id_search').val();

                window.location.href = "{{ route('admin.nguyen.lieu.thanh.pham.index') }}?ngay=" + ngay_search
                    + "&nguyen_lieu_san_xuat_id=" + nguyen_lieu_san_xuat_id_search
                    + "&san_pham_id=" + san_pham_id_search;
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Thêm mới Kho nguyên liệu Thành phẩm</h5>
                        <button class="btn btn-sm btn-primary btnShowOrHide" type="button">Mở rộng</button>
                    </div>
                    <form method="post" action="{{ route('admin.nguyen.lieu.thanh.pham.store') }}" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="nguyen_lieu_san_xuat_id">Lô SX</label>
                                <select name="nguyen_lieu_san_xuat_id" id="nguyen_lieu_san_xuat_id"
                                        class="form-control">
                                    @foreach($nlsanxuats as $nlsanxuat)
                                        <option value="{{ $nlsanxuat->id }}">
                                            {{ $nlsanxuat->PhieuSanXuat->so_lo_san_xuat }}
                                            - {{ $nlsanxuat->ten_nguyen_lieu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="san_pham_id">Mã sản phẩm</label>
                                <select name="san_pham_id" id="san_pham_id" class="form-control"
                                        onchange="changeSanPham()">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->ma_san_pham}} - {{ $product->ten_san_pham }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="ten_san_pham">Tên sản phẩm</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ten_san_pham"
                                       name="ten_san_pham" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="khoi_luong_rieng">KL rieng gr</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="khoi_luong_rieng"
                                       name="khoi_luong_rieng" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="don_vi_tinh">Đơn vị tính</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="don_vi_tinh"
                                       name="don_vi_tinh" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="so_luong">Số lượng (cái/hộp)</label>
                                <input type="text" class="form-control" id="so_luong" name="so_luong" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="price">Giá xuất kho thương mại</label>
                                <input type="text" class="form-control onlyNumber" id="price" name="price"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total_price">Tổng tiền</label>
                                <input type="text" class="form-control onlyNumber" id="total_price"
                                       name="total_price" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuThanhPham::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuThanhPham::ACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuThanhPham::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuThanhPham::INACTIVE() }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ngay_san_xuat">Hạn sử dụng</label>
                                <input type="date" class="form-control" id="ngay_san_xuat" name="ngay_san_xuat"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ghi_chu">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            changeSanPham();

            async function changeSanPham() {
                const product = $('#san_pham_id').val();
                await thongTinSanPham(product);
            }

            async function thongTinSanPham(id) {
                let url = `{{ route('api.thong.tin.san.pham.detail') }}?id=${id}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderData(data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function renderData(data) {
                const ten_san_pham = data.ten_san_pham;
                const khoi_luong_rieng = data.khoi_luong_rieng;
                const don_vi_tinh = data.don_vi_tinh;

                $('#ten_san_pham').val(ten_san_pham);
                $('#khoi_luong_rieng').val(khoi_luong_rieng);
                $('#don_vi_tinh').val(don_vi_tinh);
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover vw-100">
                        <colgroup>
                            <col width="3%">
                            <col width="7%">
                            <col width="7%">
                            <col width="7%">
                            <col width="x">
                            <col width="7%">
                            <col width="7%">
                            <col width="7%">
                            <col width="10%">
                            <col width="10%">
                            <col width="7%">
                            <col width="7%">
                            <col width="6%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Lô SX</th>
                            <th scope="col">Mã SP</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Khối lượng riêng gr</th>
                            <th scope="col">Đơn vị tính</th>
                            <th scope="col">Số lượng (cái/hộp)</th>
                            <th scope="col">Giá xuất kho thương mại</th>
                            <th scope="col">Tổng giá</th>
                            <th scope="col">Ngày sản xuất</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                                <td>{{ $data->nguyenLieuSanXuat->PhieuSanXuat->so_lo_san_xuat }}</td>
                                <td>{{ $data->sanPham->ma_san_pham }}</td>
                                <td>{{ $data->sanPham->ten_san_pham }}</td>
                                <td>{{ $data->sanPham->khoi_luong_rieng }}</td>
                                <td>{{ $data->sanPham->don_vi_tinh }}</td>
                                <td>{{ parseNumber($data->so_luong) }}</td>
                                <td>{{ parseNumber($data->price) }} VND</td>
                                <td>{{ parseNumber($data->total_price) }} VND</td>
                                <td>{{ \Carbon\Carbon::parse($data->ngay_san_xuat)->format('d-m-Y') }}</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.thanh.pham.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.thanh.pham.delete', $data->id) }}"
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
