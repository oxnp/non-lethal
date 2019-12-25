<?php

namespace App\Http\Models\Front\Products;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    /**
     * Function to load product data matching a given Paddle PID or Upgrade PID
     *
     * @param $pPID          int     The paddle (upgrade) PID
     *
     * @return mixed        The product data as object
     */
    public static  function lookupByPaddlePID($pPID)
    {
        $product = Products::wherePaddlePid($pPID)->orWhere('paddle_upgrade_pid',$pPID)->get()->toArray();

        return $product;
    }
}
