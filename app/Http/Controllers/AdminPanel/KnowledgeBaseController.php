<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\KnowledgeBase;
use App\Http\Models\Contents\KnowledgeBaseCategories;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $knowledge_base = KnowledgeBase::getKnowledgeBases();

        return view('AdminPanel.contents.knowledge_base_list')->with([
            'knowledge_base' => $knowledge_base
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = Languages::all();
        $knowledge_base_categories = KnowledgeBaseCategories::getKnowledgeBaseCategories();
        return view('AdminPanel.contents.knowledge_base_add')->with([
            'knowledge_base_categories' => $knowledge_base_categories,
            'langs'=>$langs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $next_id = KnowledgeBase::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/knowledge/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/knowledge/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }
        KnowledgeBase::addKnowledgeBase($request,$storage_image);
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
        $knowledge_base_categories = KnowledgeBaseCategories::getKnowledgeBaseCategories();
        $knowledge_base = KnowledgeBase::getKnowledgeBase($id);
        $langs = Languages::all();

        return view('AdminPanel.contents.knowledge_base_show')->with([
            'knowledge_base' => $knowledge_base,
            'langs'=>$langs,
            'knowledge_base_categories'=>$knowledge_base_categories
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
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/knowledge/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/knowledge/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        KnowledgeBase::updateKnowledgeBase($request,$storage_image);
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
