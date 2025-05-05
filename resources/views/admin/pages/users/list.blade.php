@extends('admin.layouts.master')
@section('title')
    Danh sách nhân viên
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Danh sách nhân viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Danh sách nhân viên</li>
            </ol>
        </nav>
    </div>
    <div class="w-100 mb-2 d-flex justify-content-end">
        <a href="{{ route('admin.nhan.vien.create') }}" class="btn btn-primary btn-sm">Tạo nhân viên</a>
    </div>
    <section class="section">
        <table class="table table-hover">
            <colgroup>
                <col width="5%">
                <col width="10%">
                <col width="x">
                <col width="10%">
                <col width="10%">
                <col width="20%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Họ và tên</th>
                <th scope="col">Email</th>
                <th scope="col">Số điện thoại</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">Phòng</th>
                <th scope="col">Quyền hạn</th>
                <th scope="col">Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>
                        <img class="rounded-circle" src="{{ $user->avatar }}" alt="{{ $user->full_name }}" width="100px" height="100px">
                    </td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->room }}</td>
                    <td>{{ $user->role_name == \App\Enums\RoleName::ADMIN ? 'Quản trị viên' : 'Nhân viên' }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('admin.nhan.vien.detail', $user) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.nhan.vien.delete', $user) }}" method="post">
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
        {{ $users->links('pagination::bootstrap-5') }}
    </section>
@endsection
