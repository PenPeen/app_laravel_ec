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
                    <form action="{{route('owner.images.update', ['image' => $image->id])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="-m-2">
                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="title" class="leading-7 text-sm text-gray-600">画像タイトル</label>
                                    <input id="title" type="text" name="title" value="{{$image->title}}"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            <div class="p-2 w-1/2 mx-auto">
                                <div class="relative">
                                    <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                    <div class="w-32">
                                        <x-thumbnail :filename="$image->filename" type="products"></x-thumbnail>
                                    </div>
                                    <input id="image" type="file" accept="image/jpeg,image/jpg,image/png" name="image"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-yellow-500 focus:bg-white focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>


                            <div class="p-2 w-1/2 mx-auto">
                                <div class="flex justify-around p-2 w-full mt-4">
                                    <button onclick="location.href='{{ route('owner.images.index') }}'" type="button"
                                        class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                    <button
                                        class="text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">更新する</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{route('owner.images.destroy',['image'=>$image->id])}}"
                        id="delete_form{{$image->id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-around p-2 w-full mt-24">
                            <button onclick="deleteConfirm({{$image->id}})" type="button"
                                class="w-16 md:w-24 text-white bg-gray-600 border-0 py-2 md:px-8 focus:outline-none hover:bg-gray-700 rounded">
                                削除
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/deleteConfirm.js') }}"></script>
</x-app-layout>