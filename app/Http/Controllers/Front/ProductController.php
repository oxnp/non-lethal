<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Buyers\Buyers;
use App\Http\Models\Front\Contents\ProductsPage;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function page($category,$page)
    {
        $page = ProductsPage::getPage($page);
        $categories = ProductsPageCategory::getCategoriesTolist();
        $buyer = Buyers::getBuyer();

        return view('Front.product_page')->with([
            'product_data'=>$page,
            'categories'=>$categories,
            'buyer'=>$buyer
        ]);
    }

    public function category($category)
    {

        $category_data = ProductsPageCategory::getCategory($category);
        $categories = ProductsPageCategory::getCategoriesTolist();
        $buyer = Buyers::getBuyer();

        return view('Front.product_category')->with([
            'category_data'=>$category_data,
            'category'=>$category,
            'categories'=>$categories,
            'buyer'=>$buyer
        ]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
