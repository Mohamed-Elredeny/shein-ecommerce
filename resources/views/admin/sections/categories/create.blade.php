@extends("layouts.admin")
@section("pageTitle", "Add City")
@section("content")

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <form method="post" action="{{route('categories.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الاسم</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" id="example-text-input" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الصورة</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" id="example-text-input" name="image">
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-dark w-25">أضافة</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection
