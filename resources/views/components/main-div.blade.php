@props(['header'])
<div class="w-full max-w-xl max-h-xl px-8 py-8 bg-white shadow-ms rounded-lg">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">{{$header}}</h1>
    </div>
    {{$slot}}
</div>
