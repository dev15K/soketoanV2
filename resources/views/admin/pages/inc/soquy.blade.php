@php use App\Enums\TrangThaiNguyenLieuTho;use App\Models\NguyenLieuTho; @endphp
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
                        <input type="date" class="form-control" id="ngay" name="ngay"
                               value="{{ old('ngay', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="ma_phieu">Mã phiếu</label>
                        <input type="text" class="form-control bg-secondary bg-opacity-10" id="ma_phieu"
                               name="ma_phieu" readonly
                               value="{{ old('ma_phieu', $ma_phieu) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="loai">Loại phiếu</label>
                        <select class="form-control" name="loai" id="loai" required
                                onchange="show_nha_cung_cap()">
                            <option value="0" {{ old('loai') === '0' ? 'selected' : '' }}>Phiếu Chi</option>
                            <option value="1" {{ old('loai') === '1' ? 'selected' : '' }}>Phiếu Thu</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="loai_quy_id">Tên quỹ</label>
                        <select class="form-control" name="loai_quy_id" id="loai_quy_id" required>
                            @foreach($loai_quies as $loai_quy)
                                <option
                                    value="{{ $loai_quy->id }}" {{ old('loai_quy_id') == $loai_quy->id ? 'selected' : '' }}>
                                    {{ $loai_quy->ten_loai_quy }} - Tổng
                                    tiền: {{ parseNumber($loai_quy->tong_tien_quy) }} VND
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="so_tien">Số tiền</label>
                        <input type="text" class="form-control onlyNumber" id="so_tien" name="so_tien"
                               value="{{ old('so_tien') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="noi_dung">Nội dung</label>
                    <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"
                              required>{{ old('noi_dung') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
            </form>

        </div>

    </div>
</div>

<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="card-body">
            <div class="mt-4 mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="item_ " style="width: 20%">
                        <h5>Quỹ đầu kỳ</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($ton_dau) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Tổng thu</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($thu) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Tổng chi</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($chi) }} VND</span>
                        </div>
                    </div>

                    <div class="item_ " style="width: 20%">
                        <h5>Quỹ cuối kỳ</h5>
                        <div class="p-3 border rounded-3">
                            <span class="text-danger">{{ parseNumber($ton_cuoi) }} VND</span>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover">
                <colgroup>
                    <col width="120px">
                    <col width="120px">
                    <col width="10%">
                    <col width="15%">
                    <col width="x">
                </colgroup>
                <thead>
                <tr>
                    <th scope="col">Hành động</th>
                    <th scope="col">Ngày</th>
                    <th scope="col">Loại</th>
                    <th scope="col">Tên quỹ</th>
                    <th scope="col">Số tiền</th>
                    <th scope="col">Nội dung</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.so.quy.detail', $data->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.so.quy.delete', $data->id) }}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($data->ngay)->format('d-m-Y') }}</td>
                        <td>
                            @if($data->loai == 0)
                                Phiếu Chi
                            @else
                                Phiếu Thu
                            @endif
                        </td>
                        <td>{{ $data->loaiQuy->ten_loai_quy }}</td>
                        <td>{{ parseNumber($data->so_tien) }} VND</td>
                        <td>{{ $data->noi_dung }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th scope="col" colspan="4">Tổng:</th>
                    <th scope="col" colspan="2">{{ parseNumber($datas->sum('so_tien')) }} VND</th>
                </tr>
                </tfoot>
            </table>

        </div>

    </div>
    {{ $datas->links('pagination::bootstrap-5') }}
</div>
