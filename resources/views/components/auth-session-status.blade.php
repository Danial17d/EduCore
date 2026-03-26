@props(['status'])

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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statusDiv = document.getElementById('status-div');
        const statusHeader = document.getElementById('status-header');
        const status = @json($status);

        const showStatus = (message) => {
            if (!message) return;
            statusDiv.classList.remove('hidden');
            statusHeader.textContent = message;

            setTimeout(() => {
                statusDiv.classList.remove('opacity-0');
            }, 100);

            setTimeout(() => {
                statusDiv.classList.add('opacity-0');

                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 500);
            }, 3400);
        };

        window.showStatus = showStatus;

        if (!status) return;

        setTimeout(() => {
            showStatus(status);
        }, 400);
    });
</script>
