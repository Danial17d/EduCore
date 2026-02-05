@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => ' fixed
            top-5 left-5
            z-50
            hidden opacity-0
            bg-green-700
            rounded-lg
            px-6 py-4
            text-center text-white
            transition-opacity duration-500 ease-out']) }} id="status-div">
        <h1 class="font-bold" id="status-header"></h1>
    </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statusDiv = document.getElementById('status-div');
        const statusHeader = document.getElementById('status-header');
        const status = @json($status);

        if (!status) return;


        setTimeout(() => {
            statusDiv.classList.remove('hidden');
            statusHeader.textContent = status;

            setTimeout(() => {
                statusDiv.classList.remove('opacity-0');
            }, 100);
        }, 400);

        setTimeout(() => {
            statusDiv.classList.add('opacity-0');

            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 500);
        }, 3400);
    });
</script>
