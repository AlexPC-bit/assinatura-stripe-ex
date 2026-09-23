<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Escolha seu plano</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto space-y-4">
        @if(session('warning'))
            <div class="bg-yellow-100 text-yellow-800 p-3 rounded">{{ session('warning') }}</div>
        @endif

        @foreach($plans as $plan)
            <div class="bg-white shadow rounded p-6 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">{{ $plan->name }}</h3>
                    <p class="text-gray-600">R$ {{ number_format($plan->price, 2, ',', '.') }} / mês</p>
                </div>
                <form action="{{ route('checkout.store', $plan) }}" method="POST">
                    @csrf
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Assinar</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>