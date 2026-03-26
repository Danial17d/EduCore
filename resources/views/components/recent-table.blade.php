@props(['headers' => [] ,'name'])

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-600">{{$name}}</h2>
    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-center text-sm">
            <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-4 py-3 font-semibold">{{$headers[0] ?? ""}}</th>
                <th class="px-4 py-3 font-semibold">{{$headers[1] ?? ""}}</th>
                <th class="px-4 py-3 font-semibold">{{$headers[2] ?? ""}}</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
            {{$slot}}
            </tbody>
        </table>
    </div>
</div>
