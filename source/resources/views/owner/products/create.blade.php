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
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('owner.products.store')}}" method="post">
                        @csrf
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名</label>
                                    <input type="text" required id="password" name="name" value="{{old('name')}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
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
                                    {{old('information')}}
                                    </textarea>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="price" class="leading-7 text-sm text-gray-600">価格</label>
                                    <input type="price" required id="price" name="price" value="{{old('price')}}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                    focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200
                                    ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">表示順</label>
                                    <input type="number" id="sort_order" name="sort_order" value="{{old('sort_order')}}"
                                        class=" w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
                                        focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200
                                        text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors
                                        duration-200">
                                </div>
                            </div>
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">初期在庫</label>
                                    <input type="number" id="quantity" name="quantity" value="{{old('quantity')}}"
                                        required class=" w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300
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
                                        <option value=" {{$shop->id}} ">{{$shop->name}}</option>
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
                                    outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200>
                                        @foreach($categories as $category)
                                        <optgroup label=" {{$category->name}}"></optgroup>
                                        @foreach($category->secondaryCategory as $secondary)
                                        <option value="{{$secondary->id}}">
                                            {{$secondary->name}}
                                        </option>
                                        @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <x-select-image name="image1" :images="$images"></x-select-image>
                            <x-select-image name="image2" :images="$images"></x-select-image>
                            <x-select-image name="image3" :images="$images"></x-select-image>
                            <x-select-image name="image4" :images="$images"></x-select-image>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <h1>{{$shop->is_seiling}}</h1>
                                    <div>
                                        <input class="mr-2" type="radio" name="is_selling" value="1" checked>
                                        販売中
                                    </div>
                                    <div>

                                        <input class="mr-2" type="radio" name="is_selling" value="0">
                                        停止中
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="flex justify-around p-2 w-full mt-4">
                                    <button onclick="location.href='{{ route('owner.products.index') }}'" type="button"
                                        class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                    <button
                                        class="text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">登録する</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        'use strict'
        const images = document.querySelectorAll('.image')
        
        images.forEach( image =>  {
          image.addEventListener('click', function(e){
            const imageName = e.target.dataset.id.substr(0, 6)
            const imageId = e.target.dataset.id.replace(imageName + '_', '')
            const imageFile = e.target.dataset.file
            const imagePath = e.target.dataset.path
            const modal = e.target.dataset.modal
            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile
            document.getElementById(imageName + '_hidden').value = imageId
            // MicroModal.close(modal);
        }, )
        })  
    </script>
</x-app-layout>