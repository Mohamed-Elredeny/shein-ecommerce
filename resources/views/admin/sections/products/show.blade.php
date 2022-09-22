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


                    <form method="post" action="{{route('products.update',['product'=>$record->id])}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-12 col-form-label text-center">عرض تفاصيل المنتج</label>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">التصنيف</label>
                            <div class="col-sm-10">

                                <select class="form-control" id="category_id" name="category_id" disabled
                                        required>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category->id}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">كود المنتج</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" id="example-text-input" name="name"  disabled required
                                       value="{{ $record['name'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">السعر</label>
                            <div class="col-sm-10">
                                <input class="form-control" min="0" type="number" id="example-text-input" disabled name="price"
                                       required value="{{ $record['price'] }}">
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">التفاصيل</label>
                            <div class="col-sm-10">
                                <textarea class="form-control"  id="example-text-input" name="details" required disabled>{{  $record['details'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">الصور</label>

                        </div>
                        <div class="form-group row">
                            @if($record->images)
                                <?php $images = explode('|', $record->images);
                                $counter = 0;
                                ?>
                                @foreach($images as $image)
                                    <div class="col-sm-4 text-center">
                                        <img class="d-block" src="{{asset('assets/images/products/' . $image)}}"
                                             style="height:300px;width:100%">
                                        <br>

                                    </div>
                                    <?php $counter++; ?>
                                @endforeach
                            @endif
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
