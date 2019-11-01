<?php

namespace App\Http\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['id', 'published', 'access', 'ordering', 'type', 'licsystem', 'name', 'code', 'default_majver', 'prefix_full', 'prefix_upgrade', 'prefix_temp', 'features', 'debug_mode', 'feature_prefixes', 'isbeta', 'mail_address', 'mail_from', 'mail_bcc', 'mail_subject', 'mail_body', 'upgradeable_products', 'paddle_pid', 'paddle_upgrade_pid', 'notes', 'created_at', 'updated_at'];

    public static function getProducts($per_page){
       $products =  Products::select('products.*','ur.name as access_level')
            ->leftjoin('user_role as ur','ur.id','product.access')
            ->groupby('products.id')
            ->paginate($per_page);
        return $products;
    }
}
