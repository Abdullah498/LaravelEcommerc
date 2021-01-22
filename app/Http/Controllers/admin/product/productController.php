<?php

namespace App\Http\Controllers\admin\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\generalTrait;

class productController extends Controller
{
    use generalTrait;

    public function index(){
        $products = DB::table('products')->select('products.*')->get();
        return view('en.admin.products.all',compact('products'));
    }
    public function add()
    {
        $brands = DB::table('brands')->select('brands.id','brands.name')->get();
        $suppliers = DB::table('suppliers')->select('suppliers.id','suppliers.name')->get();
        $subcategorys = DB::table('subcategorys')->select('subcategorys.id','subcategorys.name')->get();
        return view('en.admin.products.create' , compact('brands','suppliers','subcategorys'));
    }
    public function edit($id){
        $product = DB::table('products')->select('products.*')->where('products.id','=',$id)->first();
        $brands = DB::table('brands')->select('brands.id','brands.name')->get();
        $suppliers = DB::table('suppliers')->select('suppliers.id','suppliers.name')->get();
        $subcategorys = DB::table('subcategorys')->select('subcategorys.id','subcategorys.name')->get();
        return view('en.admin.products.edit',compact('product','brands','suppliers','subcategorys'));
    }
    public function create(Request $request){
        $rules = [
            'name_en'=>'required|unique:products,name_en|string|min:2',
            'name_ar'=>'required|unique:products,name_en|string|min:2',
            'price'=>'required|numeric|min:1',
            'brand_id'=>'required|integer|exists:brands,id',
            'supplier_id'=>'required|integer|exists:suppliers,id',
            'subcat_id'=>'required|integer|exists:subcategorys,id',
            'photo'=>'required|image|mimes:png,jpg,jpeg|max:1024' //max with KB
        ];
        $request->validate($rules);
        $imageName = $this->upploadImage($request->photo,'products');
        $data = $request->except('_token','photo');
        $data['photo'] = $imageName;
        DB::table('products')->insert($data);
        return redirect('admin/product/all-products');
    }
    public function update(Request $request){
        $rules = [
            'name_en'=>'required|string|min:2',
            'name_ar'=>'required|string|min:2',
            'price'=>'required|numeric|min:1',
            'brand_id'=>'required|integer|exists:brands,id',
            'supplier_id'=>'required|integer|exists:suppliers,id',
            'subcat_id'=>'required|integer|exists:subcategorys,id',
            'photo'=>'image|mimes:png,jpg,jpeg|max:1024' //max with KB
        ];
        $request->validate($rules);
        $data = $request->except('_token','_method','id');
        if($request->has('photo')){
            $imageName = $this->upploadImage($request->photo,'products');
            $data = $request->except('_token','_method','photo','id');
            $data['photo'] = $imageName;
        }
        $check = DB::table('products')->where('id','=',$request->id)->update($data);
        if($check){
            return redirect()->back()->with('Success','The product has been updated');
        }
        return redirect()->back()->with('Error','Somthing went wrong');
    }
    public function delete(Request $request)
    {
        $check = DB::table('products')->where('id','=',$request->id)->delete();
        if($check){
            $photpath = public_path('images\products\\'.$request->photo_name);
            if(file_exists($photpath)){
                unlink($photpath);
            }
            return redirect()->back()->with('Success','The product has been deleted');
        }
        return redirect()->back()->with('Error','Somthing went wrong');
    }
     
}
