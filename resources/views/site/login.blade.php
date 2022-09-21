@extends("layouts.site")
@section("content")

    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 mx-auto mt-5">
                <div class="card radius-10">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h4>تسجيل دخول</h4>
                            <p>تسجيل دخول الي حسابك</p>
                        </div>
                        <form class="form-body row g-3" method="POST" action="{{ route('auth.login.post')}}">
                            @csrf
                            <div class="col-12">
                                <label for="inputEmail" class="form-label">البريد الالكتروني</label>
                                <input type="email" name="email" class="form-control" id="inputEmail" placeholder="abc@example.com">
                            </div>
                            <div class="col-12">
                                <label for="inputPassword" class="form-label">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" id="inputPassword"
                                       placeholder="Your password">
                            </div>
                            <div class="col-12 col-lg-6">

                            </div>

                            <div class="col-12 col-lg-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">تسجيل دخول</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script src="{{asset("assets/admin/js/app.js")}}"></script>

@endsection
