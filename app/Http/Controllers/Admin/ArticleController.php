<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class ArticleController extends Controller
{
    private $viewIndex  = 'admin.pages.articles.index';
    private $viewEdit   = 'admin.pages.articles.create_edit';
    private $route      = 'admin.articles';

    public function index(Request $request): View
    {
        return view($this->viewIndex);
    }

    public function list(Request $request)
    {
        $data = Article::select('*');
        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($item) {
                return $item->is_active == 1
                    ? '<span class="badge bg-success">'.__('articles.status.active').'</span>'
                    : '<span class="badge bg-secondary">'.__('articles.status.inactive').'</span>';
            })
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    return '<img src="'.asset($item->image).'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;">';
                }
                $default = asset('assets/admin/images/default-logo.png');
                return '<img src="'.$default.'" style="max-width:60px;max-height:40px;border-radius:6px;border:1px solid #eee;opacity:.6;">';
            })
            ->addColumn('type', function ($item) {
                return __('articles.types.'.$item->type);
            })
            ->addColumn('title', function ($item) {
                return app()->getLocale() == 'ar' ? $item->title_ar : $item->title_en;
            })
            ->addColumn('actions', function ($item) {
                $editBtn = '';
                $deleteBtn = '';
                if (auth()->user()->can('articles.edit')) {
                    $editBtn = '<a href="'.route('admin.articles.edit', $item->id).'" class="btn btn-sm btn-primary mx-1"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('articles.delete')) {
                    $deleteBtn = '<form method="POST" action="'.route('admin.articles.destroy', $item->id).'" style="display:inline;">'
                        .csrf_field().method_field('DELETE').
                        '<button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm(\'هل أنت متأكد من الحذف؟\')"><i class="fa fa-trash"></i></button></form>';
                }
                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['is_active','image','actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view($this->viewEdit);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->processForm($request);
        return to_route($this->route.'.index');
    }

    public function edit($id): View
    {
        $item = Article::findOrFail($id);
        return view($this->viewEdit, compact('item'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->processForm($request, $id);
        return to_route($this->route.'.index');
    }

    public function destroy($id): RedirectResponse
    {
        $item = Article::findOrFail($id);
        if ($item->delete()) {
            // يمكنك إضافة رسالة نجاح هنا
        }
        return to_route($this->route.'.index');
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $articles = Article::where('title_ar', 'like', "%$q%")
            ->orWhere('title_en', 'like', "%$q%")
            ->select('id', 'title_ar', 'title_en')
            ->limit(20)
            ->get();
        
        $locale = app()->getLocale();
        $results = [];
        
        foreach ($articles as $article) {
            $results[] = [
                'id' => $article->id,
                'text' => $locale == 'ar' ? $article->title_ar : $article->title_en
            ];
        }
        
        return response()->json($results);
    }

    protected function processForm($request, $id = null): Article|null
    {
        $item = $id == null ? new Article() : Article::find($id);
        $data = $request->except(['_token', '_method', 'remove_image']);

        // Handle image upload and removal
        if ($request->input('remove_image') == 1) {
            if ($id && isset($item->image) && !empty($item->image) && file_exists(public_path($item->image))) {
                @unlink(public_path($item->image));
            }
            $data['image'] = null;
        } else if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('article_').'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/articles'), $filename);
            $data['image'] = 'uploads/articles/'.$filename;
        } else if ($id && isset($item->image)) {
            $data['image'] = $item->image;
        }

        // دعم الحقول ثنائية اللغة
        $data['title_ar'] = $request->input('title_ar');
        $data['title_en'] = $request->input('title_en');
        $data['description_ar'] = $request->input('description_ar');
        $data['description_en'] = $request->input('description_en');
        $data['content_ar'] = $request->input('content_ar');
        $data['content_en'] = $request->input('content_en');
        $data['type'] = $request->input('type');

        $item = $item->fill($data);
        $item->is_active = $request->filled('is_active') ? 1 : 0;
        if ($item->save()) {
            return $item;
        }
        return null;
    }
}
