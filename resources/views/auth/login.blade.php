@extends('layouts.app')
@section('content')
<section class="section bg-light"><div class="container"><div class="row justify-content-center"><div class="col-md-5"><div class="card card-sj p-4">
<h1 class="h3 fw-bold mb-2">เข้าสู่ระบบ SJ</h1>
<p class="text-muted">Admin ใช้ Email/Password ส่วนพนักงานใช้ Username/PIN</p>
<form method="post" action="{{ route('login.post') }}">@csrf
<label class="form-label">Email หรือ Username</label><input class="form-control mb-3" name="login" value="{{ old('login','admin@sjrope.test') }}" required>
<label class="form-label">Password หรือ PIN</label><input class="form-control mb-3" name="password" type="password" value="password" required>
<button class="btn btn-sj w-100">เข้าสู่ระบบ</button>
<p class="small text-muted mt-3 mb-0">Demo Admin: admin@sjrope.test / password<br>Demo Staff: mark / 1234</p>
</form></div></div></div></div></section>
@endsection
