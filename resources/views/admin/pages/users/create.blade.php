@extends('admin.layouts.master')
@section('title')
    Thêm mới nhân viên
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Thêm mới nhân viên</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang quản trị</a></li>
                <li class="breadcrumb-item active">Thêm mới nhân viên</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="post" action="{{ route('admin.nhan.vien.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirm">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="about">Giới thiệu</label>
                <input type="text" class="form-control" id="about" name="about">
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="avatar">Ảnh</label>
                    <input type="file" accept="image/*" class="form-control" id="avatar" name="avatar" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="room">Phòng ban</label>
                    <input type="text" class="form-control" id="room" name="room" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" class="form-control">
                        <option
                            value="{{ \App\Enums\UserStatus::ACTIVE() }}">{{ \App\Enums\UserStatus::ACTIVE() }}</option>
                        <option
                            value="{{ \App\Enums\UserStatus::INACTIVE() }}">{{ \App\Enums\UserStatus::INACTIVE() }}</option>
                        <option
                            value="{{ \App\Enums\UserStatus::BLOCKED() }}">{{ \App\Enums\UserStatus::BLOCKED() }}</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Thêm mới</button>
        </form>
    </section>
@endsection
