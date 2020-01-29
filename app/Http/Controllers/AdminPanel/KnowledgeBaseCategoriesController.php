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
        $knowledge_base_categories = KnowledgeBaseCategories::getKnowledgeBaseCategoriesTolist();
        return view('AdminPanel.contents.knowledge_base_categories_list')->with(['knowledge_base_categories'=>$knowledge_base_categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = Languages::all();
        return view('AdminPanel.contents.knowledge_base_categories_add')->with([
            'langs'=>$langs
        ]);;
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
        if ($request->redirect != 0){
            return redirect(route('knowledge-base-categories.index'));
        }else{
            return redirect(route('knowledge-base-categories.show',$id));
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
