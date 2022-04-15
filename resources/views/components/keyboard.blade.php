<main class="bg-slate-100 ">
    <div class="flex justify-center items-center">
        <div class="p-2 border border-solid shadow-white border-white-400 bg-black rounded-xl">
            <input readonly id="outputScreen" {{ $attributes }}
                class="mb-5 border py-4 px-2 border-gray-100 font-extrabold rounded-xl text-white text-lg bg-black text-right" value='0.000'>
            <div class="grid grid-cols-3 gap-4">
                <button
                    class="rounded-full  flex items-center justify-center  border-solid border-gray-500 bg-slate-300 p-4 w-16 h-12 text-2xl font-bold"
                    onclick="clr()">C</button>
                <button
                    class="rounded-full  flex items-center justify-center  border-solid border-gray-500 bg-slate-300 p-4 w-16 h-12 text-2xl font-bold"
                    onclick="del()">
                    <span class="fas fa-backspace"></span>
                </button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-slate-300 p-4 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'.')">
                    <span class="fas fa-circle text-xs"></span>
                </button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'7')">7</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'8')">8</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'9')">9</button>

                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'4')">4</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'5')">5</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'6')">6</button>

                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'1')">1</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'2')">2</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white px-2 w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'3')">3</button>
                <button
                    class="rounded-full  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white p-4  w-16 h-12 text-2xl font-bold"
                    onclick="display(event,'0')">0</button>
                <button
                    class="rounded-full col-span-2  flex items-center justify-center border-solid border-gray-500 bg-gray-500 text-white p-4 w-full h-12 text-2xl font-bold"
                    onclick="facturar()">Facturar</button>

            </div>
        </div>
    </div>
</main>
