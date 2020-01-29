<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Contents\UserStories;
use App\Http\Models\Front\Contents\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_stories = UserStories::getUserStories();

        return view('AdminPanel.contents.user_stories_list')->with([
            'user_stories' => $user_stories,
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
        return view('AdminPanel.contents.user_stories_add')->with([
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
        $next_id = UserStories::getLastid();
        if ($request->file('image')) {
            $file = $request->file('image');
            $storage = $file->store('image/user-stories/' . $next_id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/user-stories/' . $next_id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        UserStories::addUserStory($request,$storage_image);
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
        $user_story = UserStories::getUserStoriesById($id);
        $langs = Languages::all();
        return view('AdminPanel.contents.user_stories_show')->with([
            'user_story' => $user_story,'langs'=>$langs
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
            $storage = $file->store('image/user-stories/' . $id);
            $name_file = explode('/', $storage);
            $storage_image = '/storage/app/image/user-stories/' . $id . '/' . $name_file[3];
        }else{
            $storage_image = '';
        }

        UserStories::updateUserStories($request,$storage_image);

        if ($request->redirect != 0){
            return redirect(route('user-stories.index'));
        }else{
            return redirect(route('user-stories.show',$id));
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
