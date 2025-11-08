<script setup>
import { Pencil, Trash2, Plus } from 'lucide-vue-next';
</script>

<template>
    <main class="flex-1 py-8">
        <div class="container mx-auto px-12">

            <!-- Título -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold mb-2">Arrecadações Municipais</h2>
                <p class="text-gray-600">Visualize e analise os dados de arrecadação por período e tributo</p>
            </div>

            <!-- Filtros -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-10 w-full">
                <h3 class="text-lg font-semibold mb-1 text-gray-800">Filtros</h3>
                <p class="text-sm text-gray-500 mb-6">Selecione o período e os tributos para análise.</p>

                <div class="grid md:grid-cols-3 gap-6 items-end">

                    <!-- ano -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Ano</h4>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Início</label>
                                <select
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Fim</label>
                                <select
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- mês -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Mês</h4>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Início</label>
                                <select
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option>Janeiro</option>
                                    <option>Fevereiro</option>
                                    <option>Março</option>
                                    <option>Dezembro</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Fim</label>
                                <select
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option>Janeiro</option>
                                    <option>Fevereiro</option>
                                    <option>Março</option>
                                    <option>Dezembro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- tributos -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Tributos</h4>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400">
                                <span class="text-sm text-gray-700">IPTU</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400">
                                <span class="text-sm text-gray-700">ISS</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400">
                                <span class="text-sm text-gray-700">ITBI</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- botões -->
                <div class="mt-3">
                    <button
                        class="px-4 py-2 mr-3 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-md shadow-sm transition">
                        Aplicar
                    </button>
                    <button
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">
                        Limpar
                    </button>
                </div>
            </div>

            <!-- Total Arrecadado -->
            <div class="bg-blue-600 text-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold">Total Arrecadado</h3>
                <div class="text-4xl font-bold">R$ 12.345.678,90</div>
            </div>

            <!-- Gráficos -->
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold mb-1">Arrecadação por Mês</h4>
                    <p class="text-sm text-gray-500 mb-4">Evolução mensal das arrecadações</p>
                    <div class="h-60 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                        [Gráfico de Barras]
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold mb-1">Distribuição por Tributo</h4>
                    <p class="text-sm text-gray-500 mb-4">Participação de cada tributo no total</p>
                    <div class="h-60 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                        [Gráfico de Pizza]
                    </div>
                </div>
            </div>

            
            <!-- Alerta de Administrador -->
            <div class="m-8 p-4 border-l-4 border-blue-600 bg-blue-50 rounded-md shadow-sm">
                <h2 class="text-2xl font-semibold text-blue-800 mb-1">Acesso Administrativo</h2>
                <p class="text-gray-700">
                    Você está autenticado como <span class="font-semibold">Administrador</span> e possui permissão para
                    gerenciar os registros de arrecadações.
                </p>
            </div>


            <!-- Botão para cadastrar nova arrecadação -->
            <div class="flex justify-end mt-8">
                <button
                    class="flex items-center gap-2 bg-blue-700 py-3 px-6 rounded-md text-white font-semibold hover:bg-blue-800 transition">
                    <Plus class="w-5 h-5" />
                    Nova arrecadação
                </button>
            </div>


            <!-- Tabela -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mt-8">
                <h4 class="font-semibold mb-1">Detalhamento</h4>
                <p class="text-sm text-gray-500 mb-4">Registros individuais de arrecadação</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="text-left py-3 px-4">Tributo</th>
                                <th class="text-left py-3 px-4">Mês</th>
                                <th class="text-left py-3 px-4">Ano</th>
                                <th class="text-left py-3 px-4">Valor</th>
                                <th class="text-center py-3 px-4">Açoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">IPTU</td>
                                <td class="py-3 px-4">Janeiro</td>
                                <td class="py-3 px-4">2025</td>
                                <td class="py-3 px-4  font-semibold">R$ 1.234.567,89</td>
                                <td class="py-3 px-4 ">
                                    <div class="flex gap-2 justify-center">
                                        <div class="border border-3 p-2 rounded-md cursor-pointer hover:bg-green-700">
                                            <Pencil class="w-4 h-4" />
                                        </div>

                                        <div class="bg-red-500 p-2 rounded-md cursor-pointer hover:bg-red-600">
                                            <Trash2 class="w-4 h-4 text-white" />
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</template>