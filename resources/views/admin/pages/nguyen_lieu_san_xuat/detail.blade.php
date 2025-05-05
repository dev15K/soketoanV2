@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu sản xuất
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu sản xuất</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu sản xuất</li>
            </ol>
        </nav>
    </div>
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <section class="section">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu sản xuất</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.update', $nguyen_lieu_tinh) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ \Carbon\Carbon::parse($nguyen_lieu_tinh->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}
                                        value="{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}">{{ \App\Enums\TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == \App\Enums\TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}
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
                                @foreach($dsNLTChiTiet as $nltct)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                                @foreach($nlphanloais as $nlphanloai)
                                                    <option
                                                        {{ $nlphanloai->id == $nltct->nguyen_lieu_phan_loai_id ? 'selected' : '' }}
                                                        value="{{ $nlphanloai->id }}">{{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                        / {{ number_format($nlphanloai->tong_khoi_luong) }} kg
                                                        ~ {{ number_format($nlphanloai->gia_sau_phan_loai) }} VND
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="ten_nguyen_lieus[]" class="form-control"
                                                   value="{{ $nltct->ten_nguyen_lieu }}" required>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="khoi_luongs[]" class="form-control"
                                                   value="{{ $nltct->khoi_luong }}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeItems(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
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
    </section>
@endsection
