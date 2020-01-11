<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Image;

class Products extends Model
{
    public static function getAll()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->leftJoin('product_style as ps', 'ps.id', '=', 'p.style_id')
            ->orderBy('p.type_id', 'asc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name','ps.name as style_name')
            ->get();
        return $data;
    }
    public static function getProduct_StyleID_BrandID($style_id,$brand_id)
    {
        $data = DB::table('products as p')
            ->rightJoin('product_style as ps', 'ps.id', '=', 'p.style_id')
            ->where('p.type_id',1)
            ->where('p.style_id',$style_id)
            ->where('p.brand_id',$brand_id)
            ->orderBy('p.type_id', 'asc')
            ->select('p.*', 'ps.name as style_name')
            ->get();
        return $data;
    }
    public static function getProduct_TypeID($type_id)
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->leftJoin('product_style as ps', 'ps.id', '=', 'p.style_id')
            ->where('t.id',$type_id)
            ->orderBy('p.type_id', 'asc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name','ps.name as style_name')
            ->get();
        return $data;
    }
    public static function getProduct_TypeID_BrandID($type_id,$brand_id)
    {
        $data = DB::table('products as p')
            ->where('p.type_id',$type_id)
            ->where('p.brand_id',$brand_id)
            ->orderBy('p.type_id', 'asc')
            ->select('p.*')
            ->get();
        return $data;
    }
    public static function getExport()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->select('p.name', 'p.price', 'b.name as brand_name', 't.name as type_name')
            ->get();
        return $data;
    }
    public static function get5Prods($brand, $type)
    {
        $data = DB::table('products as p')
            ->where('p.brand_id', $brand)
            ->where('p.type_id', $type)
            ->orderBy('id', 'desc')
            ->select('p.*')
            ->take(4)->get();
        return $data;
    }

    public static function get_CuaCuon()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->where('t.id', 1)
            ->orderBy('id', 'desc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name')
            ->get();
        return $data;
    }
    public static function get_MoTor()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->where('t.id', 2)
            ->orderBy('id', 'desc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name')
            ->get();
        return $data;
    }
    public static function get_BinhLuuDien()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->where('t.id', 3)
            ->orderBy('id', 'desc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name')
            ->get();
        return $data;
    }
    public static function get_PhuKien()
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->where('t.id', 4)
            ->orderBy('id', 'desc')
            ->select('p.*', 'b.name as brand_name', 't.name as type_name')
            ->get();
        return $data;
    }
    public static function getProductId($id)
    {
        $data = DB::table('products as p')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('types as t', 't.id', '=', 'p.type_id')
            ->orderBy('id', 'desc')
            ->where('p.id', $id)
            ->select('p.*', 'b.name as brand_name', 't.name as type_name')
            ->first();
        return $data;
    }
    public static function create($data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $id = DB::table('products')->insertGetId([
            'name' => $data->name,
            'price' => $data->price,
            'description' => $data->description,
            'short_description' => $data->short_description,
            'brand_id' => $data->brand_id,
            'type_id' => $data->type_id,
            'style_id' => $data->style_id,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($data->type_id == 1 || $data->type_id == 2) {
            if ($data->range_id == null) {
                return 201;
            } else {
                foreach ($data->range_id as $r) {
                    DB::table('compatibility')->insert([
                        'product_id' => $id,
                        'range_id' => $r,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        if ($data->hasFile('product_image')) {
            //filename to store
            $filenametostore = $id . '_product.png';
            //Upload File
            $data->file('product_image')->storeAs('public/product', $filenametostore);
            $data->file('product_image')->storeAs('public/product/thumbnail', $filenametostore);

            //Resize image here
            $thumbnailpath = public_path('storage/product/thumbnail/' . $filenametostore);
            $img = Image::make($thumbnailpath)->resize(400, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
            DB::table('products')
                ->where('id', $id)
                ->update([
                    'image_link' => 'public/storage/product/' . $filenametostore,
                ]);
        }
        return 200;
    }
    public static function postEdit($data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        DB::table('products')
            ->where('id', $data->id)
            ->update([
                'name' => $data->name,
                'price' => $data->price,
                'short_description' => $data->short_description,
                'description' => $data->description,
                'brand_id' => $data->brand_id,
                'type_id' => $data->type_id,
                'style_id' => $data->style_id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        DB::table('compatibility')
            ->where('product_id', $data->id)->delete();
        if ($data->type_id == 1 || $data->type_id == 2) {
            if ($data->range_id == null) {
                return 201;
            } else {
                foreach ($data->range_id as $r) {
                    DB::table('compatibility')->insert([
                        'product_id' => $data->id,
                        'range_id' => $r,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        if ($data->hasFile('product_image')) {
            //filename to store
            $filenametostore = $data->id . '_product.png';
            //Upload File
            $data->file('product_image')->storeAs('public/product', $filenametostore);
            $data->file('product_image')->storeAs('public/product/thumbnail', $filenametostore);
            //Resize image here
            $thumbnailpath = public_path('storage/product/thumbnail/' . $filenametostore);
            $img = Image::make($thumbnailpath)->resize(400, 150, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
            DB::table('products')
                ->where('id', $data->id)
                ->update([
                    'image_link' => 'public/storage/product/' . $filenametostore,
                ]);
        }
        return 200;
    }
    public static function destroy($id)
    {
        DB::table('products')
            ->where('id', $id)->delete();
        DB::table('compatibility')
            ->where('product_id', $id)->delete();
        return 200;
    }
}