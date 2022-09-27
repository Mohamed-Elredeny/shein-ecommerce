@extends("layouts.site")
@section("content")

    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 mx-auto mt-3">
                <div class="card radius-10">
                    <div class="card-body p-4">

                        <div class="text-center">
                            <h4>تسجيل حساب جديد </h4>
                        </div>
                        <form class="form-body row g-3" method="POST" action="{{ route('auth.register.post')}}">
                            @csrf
                            <div class="col-12">
                                <label for="inputEmail" class="form-label">اسم المستخدم</label>
                                <input name="name" class="form-control" id="inputEmail"
                                       placeholder="مستخدم جديد">
                            </div>
                            <div class="col-12">
                                <label for="inputEmail" class="form-label">رقم الهاتف</label>
                                <input name="phone" class="form-control" id="inputEmail"
                                       placeholder="01068298958">
                            </div>

                            <div class="col-12">
                                <label for="inputEmail" class="form-label">البريد الالكتروني</label>
                                <input type="email" name="email" class="form-control" id="inputEmail"
                                       placeholder="abc@example.com">
                            </div>
                            <div class="col-12">
                                <label for="inputPassword" class="form-label">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" id="inputPassword"
                                       placeholder="Your password">
                            </div>
                            <div class="col-12">
                                <label for="inputPassword" class="form-label">صورة شخصية</label>
                                <input type="file" name="image" class="form-control" id="inputPassword"
                                       placeholder="Your password">
                            </div>

                            <div class="col-12 col-lg-6">

                            </div>

                            <div class="col-12 col-lg-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary-site">تسجيل</button>
                                    <span>
                                              تمتلك حساب بالفعل ؟
                                    <a href="{{route('auth.login')}}"
                                       style="display:inline-block">تسجيل دخول </a>

                                  </span>

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
