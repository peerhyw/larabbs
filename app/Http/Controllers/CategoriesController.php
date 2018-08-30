<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category){
        //读取分类id关联的话题，并按每20条分页
        $topics = Topic::where('category_id',$category->id)->with('category','user')->paginate(20);
        //传参变量话题和分类到模板中
        return view('topics.index',compact('topics','category'));
    }
}
