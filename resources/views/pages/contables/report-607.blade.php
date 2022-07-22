<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('invoices') }}
    @endslot

    <div class=" w-full ">
        <div class="inline-block py-2 w-full min-h-max h-full relative sm:px-6 lg:px-8">
            <div class="w-full flex  items-start">
                <div class="w-2/5 pb-6 relative">
                    <div
                        class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        
                    </div>
                </div>
            
                <div class="w-3/5 h-full  pl-0" x-data="{ open: true }">
                    <div class=" mx-auto relative w-full">
                        @if ($url)
                            <iframe src="{{ $url }}#view=FitH" width="700" height="700"
                                type="application/pdf">
                            </iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>


