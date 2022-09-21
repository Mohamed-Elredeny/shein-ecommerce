@extends("layouts.admin")
@section("pageTitle", 'EDIT' . strtoupper($collection) )
@section("content")

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <form method="post" action="{{route($collections . '.update',[$collection=>$record->id])}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الاسم</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" id="example-text-input" name="name"
                                       value="{{$record->name}}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الصورة</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" id="example-text-input" name="image">
                            </div>
                            <div class="col-sm-12 text-center">
                                <br>
                                @if($record->image)
                                        <img src="{{ $record->image}}" alt="" style="width:250px;height:250px">
                                @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-dark w-25">{{__('حفظ التعديلات')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection
@section("script")
    <script src="{{asset("assets/admin/js/app.js")}}"></script>

@endsection
