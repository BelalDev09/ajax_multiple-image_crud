<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // List all products
    public function index()
    {
        $products = Product::latest()->get();
        return response()->json(['status' => 'success', 'data' => $products], 200);
    }

    // Show single product
    public function show(Product $product)
    {
        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    // Create product
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['medias'] = $this->uploadMedias($request);

        $product = Product::create($data);

        return response()->json(['status' => 'success', 'message' => 'Product created successfully', 'data' => $product], 201);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Update basic fields
        $product->title = $request->title;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->save();

        // Handle multiple media files
        if ($request->hasFile('media')) {
            $mediaPaths = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('products', 'public');
                $mediaPaths[] = url('storage/' . $path);
            }
            // Replace old medias
            $product->medias = $mediaPaths;
            $product->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }


    // Delete product
    public function destroy(Product $product)
    {
        $this->deleteMedias($product);
        $product->delete();

        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }

    // Helper: upload multiple medias
    private function uploadMedias(Request $request): array
    {
        $paths = [];

        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $path = $file->store('products', 'public');
                $paths[] = url('storage/' . $path);
            }
        }

        return $paths;
    }

    // Helper: delete all medias
    private function deleteMedias(Product $product): void
    {
        if (!empty($product->medias) && is_array($product->medias)) {
            foreach ($product->medias as $media) {
                $filePath = str_replace(url('storage') . '/', '', $media);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
    }
}
