<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use Illuminate\Http\Request; use Illuminate\Support\Str; use App\Models\Article;
class ArticleController extends Controller {
 public function index(){return view('admin.articles.index',['articles'=>Article::latest()->paginate(20)]);} public function create(){return view('admin.articles.form',['article'=>new Article]);}
 public function store(Request $r){Article::create($this->data($r)); return redirect()->route('admin.articles.index')->with('success','เพิ่มบทความแล้ว');}
 public function edit(Article $article){return view('admin.articles.form',compact('article'));} public function update(Request $r, Article $article){$article->update($this->data($r,$article->id)); return back()->with('success','บันทึกบทความแล้ว');}
 public function destroy(Article $article){$article->delete(); return back()->with('success','ลบบทความแล้ว');}
 private function data(Request $r,$id=null){$d=$r->validate(['title_th'=>'required|max:255','title_en'=>'nullable|max:255','slug'=>'nullable|max:255|unique:articles,slug,'.($id?:'NULL').',id','excerpt_th'=>'nullable','excerpt_en'=>'nullable','content_th'=>'nullable','content_en'=>'nullable','status'=>'required|in:draft,published']); $d['slug']=$d['slug']?:Str::slug($d['title_en']?:$d['title_th']); $d['published_at']=$d['status']==='published'?now():null; return $d;}
}
