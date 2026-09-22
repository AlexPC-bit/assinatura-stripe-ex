<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Pagamento em processamento</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <div class="bg-white shadow rounded p-6">
            <p class="text-green-600">✓ Pagamento enviado! Sua assinatura será ativada em instantes, assim que confirmarmos com a operadora.</p>
            <a href="{{ route('dashboard') }}" class="text-blue-600 mt-4 inline-block">Voltar ao painel</a>
        </div>
    </div>
</x-app-layout>