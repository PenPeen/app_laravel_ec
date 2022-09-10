@props(['status' => 'info'])

@php
if($status === 'info'){
$bgColor = 'bg-blue-300';
}

if($status === 'update'){
$bgColor = 'bg-yellow-300';
}

if($status === 'delete'){
$bgColor = 'bg-pink-500';
}

if($status === 'alert'){
$bgColor = 'bg-red-500';
}
@endphp

@if(session('message'))
{{-- <div class="{{$bgColor}} w-1/2 mx-auto mb-3 p-2"> --}}
    <div {{ $attributes->merge(['class' => $bgColor . ' ' . '$bgColor w-1/2 mx-auto mb-3 p-2']) }} >
        {{session('message')}}
    </div>
    @endif