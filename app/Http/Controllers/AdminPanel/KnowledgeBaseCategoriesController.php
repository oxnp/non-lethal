<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\KnowledgeBaseCategories;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KnowledgeBaseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $knowledge_categories = KnowledgeBaseCategories::getKnowledgeBaseCategoriesTolist();
        return view('AdminPanel.contents.knowledge_base_categories_list')->with(['knowledge_categories'=>$knowledge_categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('AdminPanel.contents.knowledge_base_categories_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        KnowledgeBaseCategories::addKnowledgeBaseCategory($request);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $knowledge_category = KnowledgeBaseCategories::getKnowledgeBaseCategory($id);
        $langs = Languages::all();
        return view('AdminPanel.contents.knowledge_base_categories_show')->with([
            'knowledge_category'=>$knowledge_category,
            'langs'=>$langs
        ]);
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
        KnowledgeBaseCategories::updateKnowledgeBaseCategory($request);
        return redirect()->back();
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
