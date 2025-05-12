<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductSpec;
use App\Models\SolutionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $route = 'admin.products';

    /**
     * Display a listing of the products.
     */
    public function index(): View
    {
        return view('admin.pages.products.index');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $solutionTypes = SolutionType::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        
        return view('admin.pages.products.create_edit', [
            'solutionTypes' => $solutionTypes,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        if ($this->processForm($request)) {
            flash(__('products.messages.created'))->success();
        }
        
        return to_route($this->route . '.index');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id): View
    {
        $item = Product::with(['specs', 'gallery', 'relatedProducts'])->findOrFail($id);
        $solutionTypes = SolutionType::where('is_active', true)->get();
        $products = Product::where('is_active', true)->where('id', '!=', $id)->get();
        
        return view('admin.pages.products.create_edit', [
            'item' => $item,
            'solutionTypes' => $solutionTypes,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, $id): RedirectResponse
    {
        $item = Product::findOrFail($id);
        
        if ($this->processForm($request, $id)) {
            flash(__('products.messages.updated'))->success();
        }
        
        return to_route($this->route . '.index');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $item = Product::findOrFail($id);
        $item->delete();
        
        flash(__('products.messages.deleted'))->success();
        
        return to_route($this->route . '.index');
    }

    /**
     * Process form data for store or update actions.
     */
    private function processForm($request, $id = null): Product|bool
    {
        try {
            $product = $id == null ? new Product() : Product::findOrFail($id);
            $data = $request->except(['_token', '_method', 'remove_image', 'specs', 'gallery_images', 'delete_gallery', 'related_products']);
            
            // Handle main image upload and removal
            if ($request->input('remove_image') == 1) {
                // Delete old image from storage if exists
                if ($id && isset($product->image) && !empty($product->image) && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $data['image'] = null;
            } else if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $data['image'] = 'uploads/products/' . $filename;
            } else if ($id && isset($product->image)) {
                $data['image'] = $product->image; // keep old image if not replaced
            }
            
            $product = $product->fill($data);
            
            // Handle active status with checkbox
            if ($request->filled('is_active')) {
                $product->is_active = true;
            } else {
                $product->is_active = false;
            }
            
            $product->save();
            
            // Process specifications
            $this->processSpecs($request, $product);
            
            // Process gallery images
            $this->processGallery($request, $product);
            
            // Process related products
            $this->processRelatedProducts($request, $product);
            
            return $product;
        } catch (\Exception $e) {
            flash(__('products.messages.error') . ': ' . $e->getMessage())->error();
            return false;
        }
    }

    /**
     * Process product specifications.
     */
    private function processSpecs(ProductRequest $request, Product $product): void
    {
        // Delete old specs if updating
        if ($product->exists) {
            $product->specs()->delete();
        }
        
        // Add new specs
        if ($request->has('specs')) {
            foreach ($request->specs as $index => $spec) {
                if (isset($spec['key_ar']) && isset($spec['key_en'])) {
                    $product->specs()->create([
                        'key_ar' => $spec['key_ar'],
                        'key_en' => $spec['key_en'],
                        'value_ar' => $spec['value_ar'] ?? null,
                        'value_en' => $spec['value_en'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }

    /**
     * Process product gallery images.
     */
    private function processGallery(ProductRequest $request, Product $product): void
    {
        // Upload new gallery images
        if ($request->hasFile('gallery_images')) {
            $index = $product->gallery()->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $image) {
                $index++;
                $imageName = uniqid('product_gallery_') . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products/gallery'), $imageName);
                
                $product->gallery()->create([
                    'image' => 'uploads/products/gallery/' . $imageName,
                    'sort_order' => $index,
                ]);
            }
        }
        
        // Handle gallery image deletion
        if ($request->has('delete_gallery')) {
            foreach ($request->delete_gallery as $galleryId) {
                $gallery = ProductGallery::find($galleryId);
                if ($gallery) {
                    if (file_exists(public_path($gallery->image))) {
                        unlink(public_path($gallery->image));
                    }
                    $gallery->delete();
                }
            }
        }
    }

    /**
     * Process related products.
     */
    private function processRelatedProducts(ProductRequest $request, Product $product): void
    {
        // Sync related products
        if ($request->has('related_products')) {
            $product->relatedProducts()->sync($request->related_products);
        } else {
            $product->relatedProducts()->detach();
        }
    }

    /**
     * Get products for DataTables.
     */
    public function list(Request $request)
    {
        $data = Product::with('solutionType');
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('solution_name', function ($row) {
                return $row->solutionType ? $row->solutionType->name_ar : '';
            })
            ->addColumn('image', function ($row) {
                return $row->image ? '<img src="' . asset($row->image) . '" alt="" height="50">' : '';
            })
            ->addColumn('is_active', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<div class="form-check form-switch form-check-primary">
                        <input type="checkbox" ' . $checked . ' class="form-check-input" disabled>
                    </div>';
            })
            ->rawColumns(['image', 'is_active'])
            ->make(true);
    }
}
