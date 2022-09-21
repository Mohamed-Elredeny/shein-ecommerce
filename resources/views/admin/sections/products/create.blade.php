@extends("layouts.admin")
@section("pageTitle", "Add City")
@section("content")
    <style>
        .form-control {
            margin-bottom: 5px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <form method="post" action="{{route('products.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-12 col-form-label text-center">اضافة منتج جديد</label>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">التصنيف</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="category_id" name="category_id"
                                        required>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category->id}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">اسم المنتج</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" id="example-text-input" name="name" required value="{{ old('name')}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">سعر المنتج</label>
                            <div class="col-sm-4">
                                <input class="form-control" min="0" type="number" id="example-text-input" name="price"
                                       required value="{{ old('price')}}">
                            </div>
                            <label for="example-text-input" class="col-sm-2 col-form-label">نسبة الخصم</label>
                            <div class="col-sm-4">
                                <input class="form-control" min="0" type="number" id="example-text-input"
                                       name="discount"
                                       required value="{{ old('discount') ?? 0}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-12 col-form-label">الابعاد</label>
                            <div class="row form-group">
                                <label for="example-text-input" class="col-sm-1 col-form-label">الطول</label>
                                <div class="col-sm-3">
                                    <input class="form-control" name="dimensions[height]" id="example-text-input"
                                           required value="{{ old('dimensions[height]')}}">
                                </div>
                                <label for="example-text-input" class="col-sm-1 col-form-label">العرض</label>
                                <div class="col-sm-3">
                                    <input class="form-control" name="dimensions[weight]" id="example-text-input"
                                           required>
                                </div>
                                <label for="example-text-input" class="col-sm-1 col-form-label">الارتفاع</label>
                                <div class="col-sm-3">
                                    <input class="form-control" name="dimensions[length]" id="example-text-input"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-12 col-form-label">التفاصيل</label>
                            <div class="row form-group">

                                <label for="example-text-input" class="col-sm-2 col-form-label">نوع المنتج</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="example-text-input"
                                           name="specifications[material]"
                                           required>
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">لون المنتج</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="example-text-input"
                                           name="specifications[color]"
                                           required>
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">درجة اللون </label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="example-text-input"
                                           name="specifications[color_degree]"
                                           required>
                                </div>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الصور</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="file" id="example-text-input" name="images[]"
                                       multiple required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-dark w-25">اضافة</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous">
    </script>

    <script>


        $(document).ready(function () {
            $('#category_id').on('change', function () {
                var id = $(this).val();
                //alert(id);

                $.ajax({
                    url: '',
                    method: "get",
                    data: {keys: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        var cities = document.getElementById('sub_category_id');
                        console.log(data.data.subCategories);
                        cities.innerHTML = "";
                        cities.innerHTML = "<option value='0'>No Sub Categories</option>"
                        data.data.subCategories.forEach(city => cities.innerHTML += "<option value=" + city.id + ">" + city['name_en'] + "</option>");
                        //console.log(typeof data);

                        // console.log(data);
                    }
                });

            });
        });

    </script>
@endsection
