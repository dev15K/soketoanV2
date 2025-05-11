@php use App\Enums\TrangThaiNguyenLieuTho; @endphp
@php use Carbon\Carbon; @endphp
@extends('admin.layouts.master')
@section('title')
    Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active"> Kho nguyên liệu tinh</li>
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
                    <h5 class="card-title"><label for="inlineFormInputGroup">Tìm kiếm theo tên nguyên liệu tinh</label>
                    </h5>
                    <div class="col-md-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                   placeholder="Tìm kiếm theo tên nguyên liệu tinh">
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
                    <h5 class="card-title">Thêm mới Kho nguyên liệu tinh</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ten_nguyen_lieu">Tên nguyên liệu</label>
                            <input type="text" class="form-control" id="ten_nguyen_lieu" name="ten_nguyen_lieu"
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
                                        value="{{ TrangThaiNguyenLieuTho::ACTIVE() }}">{{ TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        value="{{ TrangThaiNguyenLieuTho::INACTIVE() }}">{{ TrangThaiNguyenLieuTho::INACTIVE() }}</option>
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
                                    <col width="40%">
                                    <col width="40%">
                                    <col width="15%">
                                    <col width="x">
                                </colgroup>
                                <thead>
                                <tr class="text-center">
                                    <th scope="col">Nguyên liệu phân loại</th>
                                    <th scope="col">Tên NVL sau phân loại</th>
                                    <th scope="col">Khối lượng</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbodyListNL" class="text-center">
                                <tr>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                            @foreach($nlphanloais as $nlphanloai)
                                                <option
                                                    value="{{ $nlphanloai->id }}">{{ $nlphanloai->ten_nguyen_lieu }}
                                                    / {{ number_format($nlphanloai->tong_khoi_luong) }} kg
                                                    ~ {{ number_format($nlphanloai->gia_sau_phan_loai) }} VND
                                                </option>
                                            @endforeach
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
            const baseHtml = `<tr>
                                    <td>
                                        <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                            @foreach($nlphanloais as $nlphanloai)
            <option
                value="{{ $nlphanloai->id }}">{{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            / {{ number_format($nlphanloai->tong_khoi_luong) }} kg
                                                    ~ {{ number_format($nlphanloai->gia_sau_phan_loai) }} VND
                                                </option>
                                            @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="ten_nguyen_lieus[]" class="form-control" required>
        </td>
        <td>
            <input type="number" min="0" name="khoi_luongs[]" class="form-control" required>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItems(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

            $(document).ready(function () {

            })

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
                            <th scope="col">Tên nguyên liệu</th>
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
                                <td>{{ $data->code }} - {{ $data->ten_nguyen_lieu }}</td>
                                <td>{{ Carbon::parse($data->ngay)->format('d/m/Y') }}</td>
                                <td>{{ $data->code }}</td>
                                <td>{{ number_format($data->tong_khoi_luong) }} kg</td>
                                <td>{{ number_format($data->gia_tien) }} VND</td>
                                <td>{{ $data->trang_thai }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.nguyen.lieu.tinh.detail', $data->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.nguyen.lieu.tinh.delete', $data->id) }}"
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
