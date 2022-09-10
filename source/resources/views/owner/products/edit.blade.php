<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Validation Errors -->
                <x-auth-validation-errors :errors="$errors">
                </x-auth-validation-errors>
                <x-flash-message class="my-4" status="{{session('status')}}" />
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('owner.products.update',['product' => $product->id ])}}" method="post">
                        @csrf
                        @method('put')
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名</label>
                                    <input type="text" required id="password" name="name"
                                        value="{{old('name',$product->name)}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200
                                    ease-in-out">
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">商品情報</label>
                                    <textarea required id="information" name="information" rows="8" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200
                                    ease-in-out">
                                    {{old('information',$product->information)}}
                                    </textarea>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="price" class="leading-7 text-sm text-gray-600">価格</label>
                                    <input type="price" required id="price" name="price"
                                        value="{{old('price',$product->price)}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200
                                    ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">表示順</label>
                                    <input type="number" id="sort_order" name="sort_order"
                                        value="{{old('sort_order',$product->sort_order)}}" class=" w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                        focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200
                                        text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors
                                        duration-200">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="current_quantity" class="leading-7 text-sm text-gray-600">現在庫</label>
                                    {{-- 現在個数（在庫変動があった場合に処理分岐） --}}
                                    <input type="hidden" id="current_quantity" name="current_quantity"
                                        value="{{old('current_quantity',$quantity)}}" required>
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative">
                                            <div>
                                                <input class="mr-2" type="radio" name="type"
                                                    value="{{\Constant::PRODUCT_LIST['add']}}" checked>
                                                追加
                                            </div>
                                            <div>

                                                <input class="mr-2" type="radio" name="type"
                                                    value="{{\Constant::PRODUCT_LIST['reduce']}}">
                                                削減
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class=" w-full bg-gray-100 bg-opacity-50 rounded text-base outline-none text-gray-700 py-1 px-3 leading-8">
                                        {{$quantity}}</div>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">数量</label>
                                    <input type="number" id="quantity" name="quantity" value="0" required class=" w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                        focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200
                                        text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors
                                        duration-200">
                                    <span class="text-sm">0〜99で入力</span>
                                </div>
                            </div>

                            <div class=" p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="shop_id" class="leading-7 text-sm text-gray-600">販売店舗</label>
                                    <select id="shop_id" name="shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200">
                                        @foreach($shops as $shop)
                                        <option value=" {{$shop->id}} " @if($product->shop_id === $shop->id)selected
                                            @endif>
                                            {{$shop->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="secondary_category_id"
                                        class="leading-7 text-sm text-gray-600">カテゴリー</label>
                                    <select id="secondary_category_id" name="secondary_category_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200">
                                        @foreach($categories as $category)
                                        <optgroup label="{{$category->name}}"></optgroup>
                                        @foreach($category->secondaryCategory as $secondary)
                                        <option value="{{$secondary->id}}" @if($product->secondary_category_id ===
                                            $secondary->id)selected @endif>
                                            {{$secondary->name}}
                                        </option>
                                        @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <x-select-image name="image1" currentId="{{$product->image1}}"
                                currentImage="{{$product->imageFirst->filename ?? '' }}" :images="$images">
                            </x-select-image>
                            <x-select-image name="image2" currentId="{{$product->image2}}"
                                currentImage="{{$product->imageSecond->filename ?? '' }}" :images="$images">
                            </x-select-image>
                            <x-select-image name="image3" currentId="{{$product->image3}}"
                                currentImage="{{$product->imageThird->filename ?? '' }}" :images="$images">
                            </x-select-image>
                            <x-select-image name="image4" currentId="{{$product->image4}}"
                                currentImage="{{$product->imageFourth->filename ?? '' }}" :images="$images">
                            </x-select-image>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <h1>{{$shop->is_seiling}}</h1>
                                    <div>
                                        <input class="mr-2" type="radio" name="is_selling" value="1"
                                            @if($product->is_selling === 1) checked @endif>
                                        販売中
                                    </div>
                                    <div>
                                        <input class="mr-2" type="radio" name="is_selling" value="0"
                                            @if($product->is_selling === 0) checked @endif>
                                        停止中
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="flex justify-around p-2 w-full mt-4">
                                    <button onclick="location.href='{{ route('owner.products.index') }}'" type="button"
                                        class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                    <button
                                        class="text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">更新する</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{route('owner.products.destroy',['product'=>$product->id])}}"
                        id="delete_form{{$product->id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-around p-2 w-full mt-24">
                            <button onclick="deleteConfirm({{$product->id}})" type="button"
                                class="w-16 md:w-24 text-white bg-gray-600 border-0 py-2 md:px-8 focus:outline-none hover:bg-gray-700 rounded">
                                削除
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/micromodal.js') }}"></script>
    <script src="{{ asset('/js/deleteConfirm.js') }}"></script>

</x-app-layout>