<?php

namespace App\Http\Livewire\Admin\Products\Options;

use App\Models\Image;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ProductItemOption;
use App\Traits\Upload\ImageUpload;

class Edit extends Component
{
    use ImageUpload, WithFileUploads;

    public $product;
    public $productOption;
    public $pictures = [];
    public $price;
    public $weight;
    public $quantity;

    protected $listeners = ['removeProductImage', 'setImageAsMain'];

    public function mount(Product $product, ProductItemOption $productOption)
    {
        $this->product = $product;
        $this->productOption = $productOption;
        $this->price = $productOption->price;
        $this->weight = $productOption->weight;
        $this->quantity = $productOption->quantity;
    }

    public function setImageAsMain(int $id)
    {
        $image = Image::findOrFail($id);
        
        $image->setAsMain();

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s image has been set as main.',
            'id' => Str::random(10)
        ]);
    }

    public function removeProductImage(int $id)
    {
        $image = Image::findOrFail($id);

        if ($image->is_main) {
            $this->emit('flashMessage', [
                'type' => 'error',
                'message' => 'Product\'s main image can\'t be removed.',
                'id' => Str::random(10)
            ]);

            return;
        }

        $image->delete();

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s image has been removed definitely.',
            'id' => Str::random(10)
        ]);
    }

    function updatedPictures()
    {
        $this->validate([
            'pictures.*' => [
                'required', 'image', 'mimes:png,jpg,jpeg', 'max:2200',
            ],
        ]);

        foreach ($this->pictures as $image) {
            $fileNameWithExtension = Str::random(24) . '.' . strtolower($image->getClientOriginalExtension());
            $image->storeAs('', $fileNameWithExtension, 'products');
            $this->productOption->images()->create([
                'filename' => $fileNameWithExtension,
            ]);
        }

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s image has been added successfully.',
            'id' => Str::random(10)
        ]);
    }

    public function updatedPrice(string $newValue)
    {
        $this->validate([
            'price' => 'required|numeric|between:100,20000',
        ]);

        $this->productOption->update([
            'price' => $newValue,
        ]);

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s price has been updated successfully.',
            'id' => Str::random(10)
        ]);
    }

    public function updatedQuantity(string $newValue)
    {
        $this->validate([
            'quantity' => 'required|numeric|between:1,2000',
        ]);

        $this->productOption->update([
            'quantity' => $newValue,
        ]);

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s quantity has been updated successfully.',
            'id' => Str::random(10)
        ]);
    }

    public function updatedWeight(string $newValue)
    {
        $this->validate([
            'weight' => 'required|numeric|between:1,2000',
        ]);

        $this->productOption->update([
            'weight' => $newValue,
        ]);

        $this->emit('flashMessage', [
            'type' => 'success',
            'message' => 'Product\'s weight has been updated successfully.',
            'id' => Str::random(10)
        ]);
    }

    public function render()
    {
        return view('livewire.admin.products.options.edit', [
            'images' => $this->productOption->refresh()->images,
        ]);
    }
}