<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\ProductsPage;
use App\Http\Models\Contents\ProductsPageCategories;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $products_pages = ProductsPage::getPages();

       return view('AdminPanel.contents.products_page_list')->with(['products_pages'=>$products_pages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = Languages::all();
        $categories = ProductsPageCategories::getCategories();
        return view('AdminPanel.contents.products_page_add')->with(['langs'=>$langs,'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $next_id = ProductsPage::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/product-page/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/product-page/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        ProductsPage::store($request,$storage_image);
        if ($request->redirect != 0){
            return redirect(route('products-pages.index'));
        }else{
            return redirect(route('products-pages.show',$next_id));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products_page = ProductsPage::getPage($id);
        $langs = Languages::all();
        $categories = ProductsPageCategories::getCategories();
        return view('AdminPanel.contents.products_page_show')->with(['product_page'=>$products_page,'langs'=>$langs,'categories'=>$categories]);
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
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/product-page/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/product-page/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        ProductsPage::updatePage($request,$storage_image);
        if ($request->redirect != 0){
            return redirect(route('products-pages.index'));
        }else{
            return redirect(route('products-pages.show',$id));
        }
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
