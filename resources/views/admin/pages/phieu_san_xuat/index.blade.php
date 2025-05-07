@extends('admin.layouts.master')
@section('title')
    Phiếu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Phiếu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Phiếu sản xuất</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên phiếu sản
                            xuất</label>
                    </h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên phiếu sản xuất">
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
                    <h5 class="card-title">Thêm mới Phiếu sản xuất</h5>
                    <form method="post" action="{{ route('admin.phieu.san.xuat.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_phieu">Tên phiếu</label>
                            <input type="text" class="form-control" id="ten_phieu" name="ten_phieu"
                                   value="" required>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Danh sách nguyên liệu</h4>

                                <button type="button" class="btn btn-success btn-sm" onclick="plusItem()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <table class="table table-bordered">
                                <colgroup>
                                    <col width="10%">
                                    <col width="35%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="x">
                                </colgroup>
                                <thead>
                                <tr class="text-center">
                                    <th scope="col">Loại nguyên liệu</th>
                                    <th scope="col">Nguyên liệu</th>
                                    <th scope="col">Thành phần</th>
                                    <th scope="col">Khối lượng</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                <tr>
                                    <td>
                                        <select class="form-control" name="loai_nguyen_lieu_ids[]"
                                                onchange="changeLoaiNguyenLieu(this)">
                                            <option value="nguyen_lieu_tinh">Nguyên liệu Tinh</option>
                                            <option value="nguyen_lieu_phan_loai">Nguyên liệu Phân loại</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0" name="khoi_luongs[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm disabled">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
                    </form>

                </div>

            </div>
        </div>

        <script>
            const baseHtml = ``;

            $(document).ready(function () {

            })

            async function changeLoaiNguyenLieu(el) {
                const value = $(el).val();
                if (value === 'nguyen_lieu_tinh') {
                    await listNguyenLieuTinh(el);
                } else {
                    await listNguyenLieuPhanLoai(el);
                }
            }

            async function listNguyenLieuTinh(el) {
                let url = `{{ route('api.nguyen.lieu.tinh.list') }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderData(el, data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            async function listNguyenLieuPhanLoai(el) {
                let url = `{{ route('api.nguyen.lieu.phan.loai.list') }}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    async: false,
                    success: function (data, textStatus) {
                        renderData(el, data.data);
                    },
                    error: function (request, status, error) {
                        let data = JSON.parse(request.responseText);
                        alert(data.message);
                    }
                });
            }

            function renderData(el, data) {
                console.log(data)
            }

            function plusItem() {
                $('#tbodyListNL').append(baseHtml);
            }

            function removeItems(el) {
                $(el).parent().closest('tr').remove();
            }
        </script>

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">

                    <table class="table table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="25%">
                            <col width="10%">
                            <col width="10%">
                            <col width="15%">
                            <col width="x">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên phiếu</th>
                            <th scope="col">Ngày</th>
                            <th scope="col">Mã phiếu</th>
                            <th scope="col">Tổng khối lượng</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d/m/Y') }}</td>
                                <td>{{ $data->code }}</td>
                                <td>{{ number_format($data->tong_khoi_luong) }} kg</td>
                                <td>{{ number_format($data->gia_tien) }} VND</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.phieu.san.xuat.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.phieu.san.xuat.delete', $data->id) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
