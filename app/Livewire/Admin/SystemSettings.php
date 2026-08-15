<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SystemSetting;
use App\Models\BannerSlide;
use App\Models\FeaturedProduct;
use App\Models\Product;

class SystemSettings extends Component
{
    use WithFileUploads;

    // ── Módulo búsqueda por placa ──────────────────────────────────────────
    public $enable_plate_search = true;

    // ── Gestión de banners ────────────────────────────────────────────────
    public $slides = [];

    // ── Gestión de Novedades (Featured Products) ──────────────────────────
    public $featuredProducts = [];
    public $productSearchQuery = '';
    public $productSearchResults = [];

    // Formulario nuevo slide
    public $newSlideImage;
    public $newSlideTitle    = '';
    public $newSlideSubtitle = '';
    public $newSlideBtnText  = '';
    public $newSlideBtnUrl   = '';

    public function mount()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acceso denegado.');
        }

        $this->enable_plate_search = SystemSetting::getBool('enable_plate_search', true);
        $this->loadSlides();
        $this->loadFeaturedProducts();
    }

    private function loadSlides()
    {
        $this->slides = BannerSlide::orderBy('sort_order')->get()->toArray();
    }

    // ── Placa ──────────────────────────────────────────────────────────────
    public function togglePlateSearch()
    {
        $this->enable_plate_search = !$this->enable_plate_search;
        SystemSetting::setBool('enable_plate_search', $this->enable_plate_search);
        session()->flash('success', 'Configuración de búsqueda por placa actualizada.');
    }

    // ── Banners ────────────────────────────────────────────────────────────
    public function addSlide()
    {
        $this->validate([
            'newSlideImage' => 'required|image|max:4096',
        ], [
            'newSlideImage.required' => 'Debes seleccionar una imagen.',
            'newSlideImage.image'    => 'El archivo debe ser una imagen.',
            'newSlideImage.max'      => 'La imagen no debe superar los 4MB.',
        ]);

        $path = $this->newSlideImage->store('banners', 'public');

        $count = BannerSlide::count();
        BannerSlide::create([
            'image_path'  => $path,
            'title'       => $this->newSlideTitle    ?: null,
            'subtitle'    => $this->newSlideSubtitle ?: null,
            'button_text' => $this->newSlideBtnText  ?: null,
            'button_url'  => $this->newSlideBtnUrl   ?: null,
            'sort_order'  => $count,
            'active'      => true,
        ]);

        $this->reset(['newSlideImage', 'newSlideTitle', 'newSlideSubtitle', 'newSlideBtnText', 'newSlideBtnUrl']);
        $this->loadSlides();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Slide de banner añadido correctamente.']);
    }

    public function toggleSlide($id)
    {
        $slide = BannerSlide::findOrFail($id);
        $slide->update(['active' => !$slide->active]);
        $this->loadSlides();
    }

    public function deleteSlide($id)
    {
        $slide = BannerSlide::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($slide->image_path);
        $slide->delete();
        $this->loadSlides();
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Slide eliminado.']);
    }

    public function moveUp($id)
    {
        $slide = BannerSlide::findOrFail($id);
        $prev  = BannerSlide::where('sort_order', '<', $slide->sort_order)->orderByDesc('sort_order')->first();
        if ($prev) {
            [$slide->sort_order, $prev->sort_order] = [$prev->sort_order, $slide->sort_order];
            $slide->save();
            $prev->save();
        }
        $this->loadSlides();
    }

    public function moveDown($id)
    {
        $slide = BannerSlide::findOrFail($id);
        $next  = BannerSlide::where('sort_order', '>', $slide->sort_order)->orderBy('sort_order')->first();
        if ($next) {
            [$slide->sort_order, $next->sort_order] = [$next->sort_order, $slide->sort_order];
            $slide->save();
            $next->save();
        }
        $this->loadSlides();
    }

    // ── Novedades (Featured Products) ──────────────────────────────────────
    private function loadFeaturedProducts()
    {
        $this->featuredProducts = FeaturedProduct::with('product')->orderBy('sort_order')->get()->toArray();
    }

    public function updatedProductSearchQuery()
    {
        if (strlen($this->productSearchQuery) >= 3) {
            $this->productSearchResults = Product::where('name', 'like', '%' . $this->productSearchQuery . '%')
                ->orWhere('oem_code', 'like', '%' . $this->productSearchQuery . '%')
                ->orWhere('supplier_code', 'like', '%' . $this->productSearchQuery . '%')
                ->take(10)
                ->get()
                ->toArray();
        } else {
            $this->productSearchResults = [];
        }
    }

    public function addFeaturedProduct($productId)
    {
        if (FeaturedProduct::where('product_id', $productId)->exists()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Este producto ya está en destacados.']);
            return;
        }

        $count = FeaturedProduct::count();
        FeaturedProduct::create([
            'product_id' => $productId,
            'sort_order' => $count,
            'active' => true,
        ]);

        $this->productSearchQuery = '';
        $this->productSearchResults = [];
        $this->loadFeaturedProducts();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Producto añadido a novedades.']);
    }

    public function toggleFeaturedProduct($id)
    {
        $fp = FeaturedProduct::findOrFail($id);
        $fp->update(['active' => !$fp->active]);
        $this->loadFeaturedProducts();
    }

    public function deleteFeaturedProduct($id)
    {
        FeaturedProduct::findOrFail($id)->delete();
        $this->loadFeaturedProducts();
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Producto destacado eliminado.']);
    }

    public function moveUpFeatured($id)
    {
        $fp = FeaturedProduct::findOrFail($id);
        $prev = FeaturedProduct::where('sort_order', '<', $fp->sort_order)->orderByDesc('sort_order')->first();
        if ($prev) {
            [$fp->sort_order, $prev->sort_order] = [$prev->sort_order, $fp->sort_order];
            $fp->save();
            $prev->save();
        }
        $this->loadFeaturedProducts();
    }

    public function moveDownFeatured($id)
    {
        $fp = FeaturedProduct::findOrFail($id);
        $next = FeaturedProduct::where('sort_order', '>', $fp->sort_order)->orderBy('sort_order')->first();
        if ($next) {
            [$fp->sort_order, $next->sort_order] = [$next->sort_order, $fp->sort_order];
            $fp->save();
            $next->save();
        }
        $this->loadFeaturedProducts();
    }

    public function render()
    {
        return view('livewire.admin.system-settings')->layout('layouts.app');
    }
}
