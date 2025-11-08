<script setup>
    import { DollarSign, ChevronRight, ArrowRight, Calculator } from 'lucide-vue-next';
</script>

<template>
    <main class="flex-1">

        <!-- Banner -->
        <section class="bg-gradient-to-br from-blue-700 to-blue-500 py-16 text-white">
            <div class="container mx-auto px-16">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        Transparência nas Arrecadações Municipais
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-8 leading-relaxed">
                        Acompanhe em tempo real os valores arrecadados pelos tributos municipais de Maceió. Dados
                        atualizados e acessíveis para todos os cidadãos.
                    </p>
                    <div class="flex gap-4">
                        <a href="/arrecadacoes"
                            class="bg-white text-blue-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition flex items-center">
                            Ver Arrecadações
                            <ChevronRight class="ml-2 text-blue/700" />
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <!-- KPIs -->
        <section class="py-8 bg-gray-50">
            <div class="container mx-auto px-8 grid gap-4 md:grid-cols-3 max-w-6xl">

                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-sm font-medium text-gray-500">Total Arrecadado</h3>
                        <DollarSign class="text-blue-700" />
                    </div>
                    <div class="text-2xl font-bold text-gray-900">R$ 12.450.000</div>
                    <p class="text-xs text-gray-500 mt-1">Acumulado em {{ new Date().getFullYear() }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-sm font-medium text-gray-500">Tributo Destaque</h3>
                        <ArrowRight class="text-orange-700" />
                    </div>
                    <div class="text-2xl font-bold text-gray-900">ISS</div>
                    <p class="text-xs text-gray-500 mt-1">R$ 6.200.000</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-sm font-medium text-gray-500">Registros</h3>
                        <Calculator class="text-green-500" />
                    </div>
                    <div class="text-2xl font-bold text-gray-900">324</div>
                    <p class="text-xs text-gray-500 mt-1">Lançamentos cadastrados</p>
                </div>

            </div>
        </section>


        <!-- Sobre o portal -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Sobre o Portal</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        O portal <strong>Sefaz é Massa</strong> foi criado para promover a transparência na gestão dos
                        recursos públicos municipais.
                        Aqui você encontra informações detalhadas sobre a arrecadação de tributos como
                        <strong>IPTU</strong>, <strong>ISS</strong> e <strong>ITBI</strong>,
                        fortalecendo a cidadania e o controle social.
                    </p>

                    <div class="grid gap-6 md:grid-cols-3 text-left">
                        <div class="border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">IPTU</h3>
                            <p class="text-sm text-gray-500">Imposto Predial e Territorial Urbano</p>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">ISS</h3>
                            <p class="text-sm text-gray-500">Imposto Sobre Serviços</p>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">ITBI</h3>
                            <p class="text-sm text-gray-500">Imposto de Transmissão de Bens Imóveis</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>