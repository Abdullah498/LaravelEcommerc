@extends('en.admin.template.layout')
@section('title','Add Product')
@section('content')
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{asset('admin/product/create')}}" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name EN</label>
                  <input type="text" name='name_en' class="form-control" id="exampleInputEmail1" value="{{old('name_en')}}">
                </div>
                @error('name_en')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="form-group">
                  <label for="exampleInputPassword1">Name AR</label>
                  <input type="text" name="name_ar" class="form-control" id="exampleInputPassword1" value="{{old('name_ar')}}">
                </div>
                @error('name_ar')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="form-group">
                    <label for="detail_en">Details EN</label>
                    <textarea name="detail_en" id="detail_en" class="form-control" cols="30" rows="10">{{old('detail_en')}}</textarea>
                </div>
                <div class="form-group">
                    <label for="detail_ar">Details AR</label>
                    <textarea name="detail_ar" id="detail_ar" class="form-control" cols="30" rows="10">{{old('detail_ar')}}</textarea>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Price</label>
                    <input type="text" name="price" class="form-control" id="exampleInputPassword2" value="{{old('price')}}">
                </div>
                @error('price')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="form-group">
                    <label for="select1">Brand</label>
                    <select name="brand_id" class="form-control" id="select1">
                      @foreach ($brands as $value)
                        <option {{old('brand_id')==$value->id? 'selected':''}}  value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                </div>
                @error('brand_id')
                    <div class="alert alert-danger">{{$message}}</div>
                @enderror
                <div class="form-group">
                  <label for="select2">Supplier</label>
                  <select name="supplier_id" class="form-control"  id="select2">
                    @foreach ($suppliers as $value)
                      <option {{old('supplier_id')==$value->id? 'selected':''}} value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                  </select>
                </div>
                @error('supplier_id')
                  <div class="alert alert-danger">{{$message}}</div>
                @enderror
              <div class="form-group">
                <label for="select3">Subcategory</label>
                <select name="subcat_id" class="form-control"  id="select3">
                  @foreach ($subcategorys as $value)
                    <option {{old('subcat_id')==$value->id? 'selected':''}} value="{{$value->id}}">{{$value->name}}</option>
                  @endforeach
                </select>
              </div>
              @error('subcat_id')
                <div class="alert alert-danger">{{$message}}</div>
              @enderror
              {{-- <div class="form-row">
                <div class="col-3">
                  <img src="{{asset('images/products/'.$product->photo)}}" alt="">
                </div>
              </div> --}}
              <div class="form-group">
                  <label for="exampleFormControlFile1">Product Image</label>
                  <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="photo" class="custom-file-input" id="exampleFormControlFile1">
                        <label class="custom-file-label" for="exampleFormControlFile1">Choose file</label>
                      </div>
                  </div>
              </div>
              @error('photo')
                <div class="alert alert-danger">{{$message}}</div>
              @enderror
            </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
    </div>
@endsection