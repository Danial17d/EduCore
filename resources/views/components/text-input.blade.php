@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => ' border-2 border-gray-300 px-3 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
