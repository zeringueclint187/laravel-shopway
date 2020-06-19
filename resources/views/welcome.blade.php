@extends('layouts.app')

@section('meta-desc')
    <meta name="description" content="Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem nam veniam tempora fugit fuga pariatur atque maiores consequuntur asperiores dolores! Facere natus vitae odit, quis corporis recusandae ad consectetur numquam!"/>
@endsection

@section('meta-title')
    Welcome
@endsection

@section('modal')
<x-modal>
    <x-product.content-modal />
</x-modal>
@endsection

@section('content')

    <x-includes.hero />

    <section class="bg-white py-6">
        <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">
            <nav id="store" class="w-full z-30 top-0 px-6 py-1" x-data="{search : false}">
                <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">
                    <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                        Store
                    </a>
                </div>
            </nav>
            @forelse ($products as $product)
            <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
                <div class="cursor-pointer"
                    @click="
                        isDialogOpen = true;
                        $refs.productModalRoute.href='{{ route('products.show', $product) }}';
                        $refs.productModalTitle.innerText='{{ ucfirst($product->title) }}';
                        $refs.productModalDesc.innerText='{{ $product->excerpt }}';
                        $refs.productModalPrice.innerText='{{ Format::priceWithTaxAndCurrency($product->firstReference->price) }}';
                        $refs.productModalImg.src='{{ $product->firstReference->imagePath }}';
                        $refs.productModalId.setAttribute('data-product', {{ $product->firstReference->id }});
                ">
                    <div class="img hover:grow hover:shadow-lg w-72 h-48">
                        <img class="w-full h-full object-cover rounded shadow-lg" src="https://picsum.photos/300/300?random={{ mt_rand(1, 15) }}">
                    </div>
                    <div class="pt-3 flex items-center justify-between">
                        <p class="">{{ ucfirst($product->title) }}</p>
                        <a href="#" @click.stop="alert('ok')">
                            <svg class="h-6 w-6 fill-current text-gray-500 hover:text-red-400 z-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12,4.595c-1.104-1.006-2.512-1.558-3.996-1.558c-1.578,0-3.072,0.623-4.213,1.758c-2.353,2.363-2.352,6.059,0.002,8.412 l7.332,7.332c0.17,0.299,0.498,0.492,0.875,0.492c0.322,0,0.609-0.163,0.792-0.409l7.415-7.415 c2.354-2.354,2.354-6.049-0.002-8.416c-1.137-1.131-2.631-1.754-4.209-1.754C14.513,3.037,13.104,3.589,12,4.595z M18.791,6.205 c1.563,1.571,1.564,4.025,0.002,5.588L12,18.586l-6.793-6.793C3.645,10.23,3.646,7.776,5.205,6.209 c0.76-0.756,1.754-1.172,2.799-1.172s2.035,0.416,2.789,1.17l0.5,0.5c0.391,0.391,1.023,0.391,1.414,0l0.5-0.5 C14.719,4.698,17.281,4.702,18.791,6.205z" />
                            </svg>
                        </a>
                    </div>
                    <p class="pt-1 text-gray-800 font-bold text-xl">{{ Format::priceWithTaxAndCurrency($product->firstReference->price) }}</p>
                </div>
            </div>
            @empty
            <p>Sorry, no products can be displayed for the moment!</p>
            @endforelse
        </div>
    </section>

    <x-includes.newsletter />
@endsection

@section('extra-js')
<script>
    window.onload = () => {
        window.livewire.on('addToCart', () => {
            let modalProductElement = document.querySelector('.modal-product');
            window.livewire.emit('addToCartFromModal', modalProductElement.getAttribute('data-product'));
        });
    }
</script>
@endsection
