<?php

namespace App\Http\Models\Products;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Products\MainProducts;
use DB;

class Products extends Model
{
    protected $fillable = ['id', 'published', 'access', 'ordering', 'type', 'licsystem', 'name', 'code', 'default_majver', 'prefix_full', 'prefix_upgrade', 'prefix_temp', 'features', 'debug_mode', 'feature_prefixes', 'isbeta', 'mail_address', 'mail_from', 'mail_bcc', 'mail_subject', 'mail_body', 'upgradeable_products', 'paddle_pid', 'paddle_upgrade_pid', 'notes', 'created_at', 'updated_at'];

    public static function getProducts($per_page){
       $products =  Products::select('products.*','ur.name as access_level')
            ->leftjoin('user_role as ur','ur.id','products.access')
            ->groupby('products.id')
            ->paginate($per_page);
        return $products;
    }
    public static function getProductById($id){
       $products =  Products::find($id);
        return $products;
    }

    public static function getUpgradeableProduct(){
        $products = Products::select('products.name as name', 'products.id as id');
        $maim_products = MainProducts::select(DB::raw("CONCAT(main_products.prod_desc,'(Legacy)') as name"),DB::raw("CONCAT(main_products.id,'L') as id"))->union($products)->get()->toArray();
        return $maim_products;
    }

    public static function getAssignedPaddlePIDs($id){
        $paddle_pid = Products::select('paddle_pid')
            ->where('paddle_pid','!=','')
            ->where('paddle_pid','!=',DB::raw("(select paddle_pid from products where id = ".$id.")"));

        $paddle_upgrade_pid = Products::select('paddle_upgrade_pid as pid')
            ->where('paddle_upgrade_pid','!=','')
            ->where('paddle_upgrade_pid','!=',DB::raw("(select paddle_upgrade_pid from products where id = ".$id.")"))
            ->union($paddle_pid)
            ->get()
            ->toArray();
    return $paddle_upgrade_pid;
    }

    public static function getProductTypeById($id){
        $type = Products::select('type')->whereId($id)->get()->toArray();
        return $type[0]['type'];
    }
}
