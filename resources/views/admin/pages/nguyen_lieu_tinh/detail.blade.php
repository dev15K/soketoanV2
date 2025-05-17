@php use App\Enums\TrangThaiNguyenLieuTho;use Carbon\Carbon; @endphp
@php @endphp
@extends('admin.layouts.master')
@section('title')
    Chỉnh sửa Kho nguyên liệu tinh
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Chỉnh sửa Kho nguyên liệu tinh</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa Kho nguyên liệu tinh</li>
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
                    <h5 class="card-title">Chỉnh sửa Kho nguyên liệu tinh</h5>
                    <form method="post" action="{{ route('admin.nguyen.lieu.tinh.update', $nguyen_lieu_tinh) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ma_phieu">Mã phiếu</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                                       name="ma_phieu" value="{{ $ma_phieu }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="code">Mã lô hàng</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10" id="code" name="code"
                                       value="{{ $code }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ngay">Ngày</label>
                                <input type="date" class="form-control" id="ngay" name="ngay"
                                       value="{{ Carbon::parse($nguyen_lieu_tinh->ngay)->format('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai" class="form-control">
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTho::ACTIVE() ? 'selected' : '' }}
                                        value="{{ TrangThaiNguyenLieuTho::ACTIVE() }}">{{ TrangThaiNguyenLieuTho::ACTIVE() }}</option>
                                    <option
                                        {{ $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTho::INACTIVE() ? 'selected' : '' }}
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
                                @foreach($dsNLTChiTiet as $nltct)
                                    <tr>
                                        <td>
                                            <select class="form-control" name="nguyen_lieu_phan_loai_ids[]">
                                                @foreach($nlphanloais as $nlphanloai)
                                                    <option
                                                        {{ $nlphanloai->id == $nltct->nguyen_lieu_phan_loai_id ? 'selected' : '' }}
                                                        value="{{ $nlphanloai->id }}">
                                                        {{ $nlphanloai->nguyenLieuTho->code }}
                                                        - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="ten_nguyen_lieus[]">
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nụ cao cấp (NCC)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nụ cao cấp (NCC)">
                                                    Nguyên liệu nụ cao cấp (NCC)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nụ VIP (NVIP)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nụ VIP (NVIP)">
                                                    Nguyên liệu nụ VIP (NVIP)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nguyên liệu nhang (NLN)' ? 'selected' : '' }}
                                                    value="Nguyên liệu nhang (NLN)">
                                                    Nguyên liệu nhang (NLN)
                                                </option>
                                                <option
                                                    {{  $nltct->ten_nguyen_lieu == 'Nguyên liệu vòng (NLV)' ? 'selected' : '' }}
                                                    value="Nguyên liệu vòng (NLV)">
                                                    Nguyên liệu vòng (NLV)
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Tăm tre' ? 'selected' : '' }}
                                                    value="Tăm tre">
                                                    Tăm tre
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Keo' ? 'selected' : '' }}
                                                    value="Keo">
                                                    Keo
                                                </option>
                                                <option
                                                    {{ $nltct->ten_nguyen_lieu == 'Nấu dầu' ? 'selected' : '' }}
                                                    value="Nấu dầu">
                                                    Nấu dầu
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="khoi_luongs[]" class="form-control onlyNumber"
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
            <option value="{{ $nlphanloai->id }}">
               {{ $nlphanloai->nguyenLieuTho->code }}
            - {{ $nlphanloai->nguyenLieuTho->ten_nguyen_lieu }}
            </option>
@endforeach
            </select>
        </td>
        <td>
            <select class="form-control" name="ten_nguyen_lieus[]">
                                            <option value="Nguyên liệu nụ cao cấp (NCC)">Nguyên liệu nụ cao cấp (NCC)</option>
                                            <option value="Nguyên liệu nụ VIP (NVIP)">Nguyên liệu nụ VIP (NVIP)</option>
                                            <option value="Nguyên liệu nhang (NLN)">Nguyên liệu nhang (NLN)</option>
                                            <option value="Nguyên liệu vòng (NLV)">Nguyên liệu vòng (NLV)</option>
                                            <option value="Tăm tre">Tăm tre</option>
                                            <option value="Keo">Keo</option>
                                            <option value="Nấu dầu">Nấu dầu</option>
                                        </select>
        </td>
        <td>
            <input type="text" min="0" name="khoi_luongs[]" class="form-control" required>
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
