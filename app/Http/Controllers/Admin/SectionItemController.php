<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Http\Request;

class SectionItemController extends Controller
{
    public function store(Request $request, $page_id, $section_id)
    {
        try {
            $section = Section::findOrFail($section_id);
            $type = $request->input('type');
            $content = $request->input('content');
            // إزالة المتغيرات غير المستخدمة
            // معالجة أنواع خاصة
            if ($type === 'image' && $request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads/sections', 'public');
                $content = ['url' => asset('storage/' . $path)];
            } elseif ($type === 'gallery' && $request->hasFile('gallery')) {
                $images = [];
                foreach ($request->file('gallery') as $img) {
                    $imgPath = $img->store('uploads/sections/gallery', 'public');
                    $images[] = asset('storage/' . $imgPath);
                }
                $content = ['images' => $images];
            } elseif ($type === 'article') {
                $c = json_decode($content, true);
                // العمل مع الهيكل الجديد: selected_articles و article_ids
                $content = $c;
            } elseif ($type === 'business') {
                $c = json_decode($content, true);
                // عدم استخراج المعرف منفصلاً
                $content = $c;
            } elseif ($type === 'list') {
                $c = json_decode($content, true);
                $content = $c;
            } elseif ($type !== 'image' && $type !== 'gallery') {
                $content = json_decode($content, true);
            }
            $item = $section->items()->create([
                'type' => $type,
                'content' => $content,
                'order' => $section->items()->count(),
                // إزالة article_id و business_id لأنهما غير موجودين في الجدول
                // سيتم تخزين كل البيانات في حقل content كـ JSON
            ]);
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function update(Request $request, $item_id)
    {
        try {
            $item = SectionItem::findOrFail($item_id);
            $type = $request->input('type');
            $content = $request->input('content');
            
            // Handle image updates
            if ($type === 'image' && $request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads/sections', 'public');
                $content = ['url' => asset('storage/' . $path)];
            } 
            // Handle gallery updates
            elseif ($type === 'gallery' && $request->hasFile('gallery')) {
                $images = [];
                foreach ($request->file('gallery') as $img) {
                    $imgPath = $img->store('uploads/sections/gallery', 'public');
                    $images[] = asset('storage/' . $imgPath);
                }
                $content = ['images' => $images];
            } 
            // Handle other content types that come as JSON
            elseif ($type === 'article' || $type === 'business' || $type === 'list' || ($type !== 'image' && $type !== 'gallery')) {
                $content = json_decode($content, true);
            }
            
            // Use the existing content if no new content is provided
            $existingContent = $item->content;
            if ($type === 'image' && !$request->hasFile('image')) {
                $content = $existingContent;
            } elseif ($type === 'gallery' && !$request->hasFile('gallery')) {
                $content = $existingContent;
            }
            
            // Update the item
            $item->update([
                'type' => $type,
                'content' => $content,
            ]);
            
            return response()->json(['success' => true, 'item' => $item]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function destroy($page_id, $section_id, $item_id)
    {
        $item = SectionItem::findOrFail($item_id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function sort(Request $request, $page_id, $section_id)
    {
        $order = $request->input('order', []);
        foreach ($order as $idx => $itemId) {
            SectionItem::where('section_id', $section_id)->where('id', $itemId)->update(['order' => $idx]);
        }
        return response()->json(['success' => true]);
    }
}
