<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      オーナー 一覧
    </h2>
  </x-slot>

  {{-- フラッシュメッセージ --}}
  <x-flash-message status="{{session('status')}}" />

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="md:p-6 bg-white border-b border-gray-200">
          <div>
            <section class="text-gray-600 body-font">
              <div class="container px-3 md:px-5 mx-auto">
                <div class="flex justify-end mb-4">
                  <button onclick="location.href='{{route('admin.owners.create')}}'"
                    class="text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">新規登録</button>
                </div>
                <div class="w-full mx-auto overflow-auto">
                  <table class="table-auto w-full text-left whitespace-no-wrap">
                    <thead>
                      <tr>
                        <th
                          class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                          name</th>
                        <th
                          class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                          email</th>
                        <th
                          class="md:px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                          created</th>
                        <th class="px-2 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                        </th>
                        <th class="px-2 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($owners as $owner)
                      <tr>
                        <td class="md:px-4 py-3">{{$owner->name}}</td>
                        <td class="md:px-4 py-3">{{$owner->email}}</td>
                        <td class="md:px-4 py-3">{{$owner->created_at->diffForHumans()}}</td>
                        <td class="text-center">
                          <button onclick="location.href='{{route('admin.owners.edit',['owner' => $owner->id])}}'"
                            class="w-16 md:w-24 text-base text-white bg-gray-400 border-0 py-2 md:px-8 focus:outline-none
                            hover:bg-gray-600 rounded">
                            編集
                          </button>
                        </td>
                        <td class="text-center">
                          <form action="{{route('admin.owners.destroy',['owner'=>$owner->id])}}"
                            id="delete_form{{$owner->id}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button onclick="deleteConfirm({{$owner->id}})" type="button"
                              class="w-16 md:w-24 text-white bg-gray-600 border-0 py-2 md:px-8 focus:outline-none hover:bg-gray-700 rounded">
                              削除
                            </button>
                          </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="mt-3">
                    {{$owners->links()}}
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('/js/deleteConfirm.js') }}"></script>
</x-app-layout>