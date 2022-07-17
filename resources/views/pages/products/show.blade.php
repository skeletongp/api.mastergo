<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('products.show', $product) }}
    @endslot

    <div class=" w-full ">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 py-24 mx-auto max-w-4xl relative">
                @can('Editar Productos')
                    <div class="absolute top-1 right-2">
                        <a href="{{ route('products.edit', $product) }}"
                            class=" right-0  rounded-full h-8 w-max px-3 py-1 space-x-2 shadow xl flex items-center ">
                            <span class="far fa-pen-square text-xl"></span>
                            <span>Editar</span>
                        </a>
                    </div>
                @endcan
                <div class="grid grid-cols-3 gap-6 max-w-6xl w-full mx-auto">
                    <div class="col-span-2">
                        <livewire:products.product-show :product="$product" />
                    </div>
                    <div class="flex items-center justify-center col-span-1">
                        <div draggable="true" id="drop-zone"
                            class="w-48 h-48 rounded-full dragable  bg-center bg-cover border-4 border-gray-300 bg-no-repeat"
                            style="background-image: url({{ $product->photo }})">

                        </div>
                    </div>
                    <input type="file" id="myfile" hidden>
                </div>
            </div>
        </section>
    </div>
    @push('js')
        <script>
            const dropZone = document.querySelector('#drop-zone');
            const inputElement = document.querySelector('#myfile');

            inputElement.addEventListener('change', function(e) {
                const clickFile = this.files[0];
                if (clickFile) {

                    const reader = new FileReader();
                    reader.readAsDataURL(clickFile);
                    reader.onloadend = function() {
                        const result = reader.result;
                        let src = this.result;
                        console.log(src);
                    }
                }
            })
            dropZone.addEventListener('click', () => inputElement.click());
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                let file = e.dataTransfer.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    window.livewire.emit('productPhotoChange', reader.result);
                }
                reader.readAsDataURL(file);
            });
        </script>
    @endpush

</x-app-layout>
