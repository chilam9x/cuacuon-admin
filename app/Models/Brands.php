<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Brands extends Model
{
    public static function getAll()
    {
        $data = DB::table('brands as b')
        ->join('types as t','t.id','=','b.type_id')
        ->select('b.*','t.name as type_name','t.id as type_id')
        ->orderBy('b.id', 'desc')->get();
        return $data;
    }

    public static function getByType($id)
    {
        $data = DB::table('brands')->where('type_id', $id)->orderBy('id', 'desc')->get();
        return $data;
    }
    public static function ajaxGetBrands($id)
    {
        $data = DB::table('brands')->where('type_id', $id)->select('id', 'name as text')->get();
        return $data;
    }
    public static function create($data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        DB::table('brands')->insert([
            'name' => $data->name,
            'type_id' => $data->type_id,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return 200;
    }
    public static function edit($data)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        DB::table('brands')
            ->where('id', $data->id)
            ->update([
                'name' => $data->name,
                'type_id' => $data->type_id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        return 200;
    }
    public static function destroy($id)
    {
        DB::table('brands')
            ->where('id', $id)->delete();
        return 200;
    }
}