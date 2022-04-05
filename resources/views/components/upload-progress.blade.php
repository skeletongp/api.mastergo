<div x-data="{ isUploading: false, progress: 0 }" x-cloak x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">
    {{-- input de la foto --}}
    {{ $slot }}
    <!-- Progress Bar -->
    <div x-show="isUploading">
        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
            <div class="bg-green-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                :style="{width:progress+'%'}" x-html="progress+'%'"></div>
        </div>
    </div>
</div>
