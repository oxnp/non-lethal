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

        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/products/'.$category;
        $category_data = ProductsPageCategory::getCategory($category);
        $breadcrumbs[0]['text'] = $category_data['category'][0]->name;

        $breadcrumbs[1]['url'] = '/products/'.$category.'/'.$page;
        $page = ProductsPage::getPage($page);

        $breadcrumbs[1]['text'] = $page[0]->title;


        $categories = ProductsPageCategory::getCategoriesTolist();
        $buyer = Buyers::getBuyer();

        return view('Front.product_page')->with([
            'product_data'=>$page,
            'categories'=>$categories,
            'buyer'=>$buyer,
            'breadcrumbs' => $breadcrumbs,
            'meta_title' => $page[0]->title
        ]);
    }

    public function category($category)
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/products/'.$category;

        $category_data = ProductsPageCategory::getCategory($category);

        $breadcrumbs[0]['text'] = $category_data['category'][0]->name;

        $categories = ProductsPageCategory::getCategoriesTolist();
        $buyer = Buyers::getBuyer();


      //  dd($category_data);
        return view('Front.product_category')->with([
            'category_data'=>$category_data,
            'category'=>$category,
            'categories'=>$categories,
            'buyer'=>$buyer,
            'breadcrumbs' => $breadcrumbs,
            'meta_title' => $category_data['category'][0]->name
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
