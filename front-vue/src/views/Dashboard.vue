<script setup>
import { Pencil, Trash2, Plus } from 'lucide-vue-next';
import axios from 'axios';
import { onMounted, ref } from 'vue'
import Modal from '../components/model.vue';
import Chart from 'chart.js/auto'

const data = ref({});
const showModal = ref(false);
const isEditing = ref(false);
const dadoSelecionado = ref(null);
const isLogado = ref(!!localStorage.getItem('token'))

// Filtros
const tributos = ref([]);
const anoInicio = ref();
const anoFim = ref();
const mesInicio = ref();
const mesFim = ref();

const param = ref('')

// fas string com os parâmetros
function aplicarFiltros() {
    let query = [''];

    if (anoInicio.value) query.push(`ano_inicio=${anoInicio.value}`);
    if (anoFim.value) query.push(`ano_fim=${anoFim.value}`);
    if (mesInicio.value) query.push(`mes_inicio=${mesInicio.value}`);
    if (mesFim.value) query.push(`mes_fim=${mesFim.value}`);
    if (tributos.value.length > 0) query.push(`tributo=${tributos.value.join(',')}`);

    param.value = query.join('&');
    console.log(param.value)

    getDataDashBoard(param.value);
}

// limpa os filtros
function limparFiltros() {
    anoInicio.value = null;
    anoFim.value = null;
    mesInicio.value = null;
    mesFim.value = null;
    tributos.value = []

    getDataDashBoard();
}

// pega os dados da Api
function getDataDashBoard(param) {
    // se for passado parametros ele manda na requisição
    const url = param ? `/api/arrecadacoes/dashboard?${param}` : `/api/arrecadacoes/dashboard`

    // puxando os dados da API
    axios.get(url)
        .then((response) => {
            data.value = response.data.data

            // Cria o gráfico após garantir que o DOM e os dados estão prontos
            if (data.value.graficos?.arrecadacao_mensal?.length) {
                criarGraficoBarras(data.value.graficos.arrecadacao_mensal, 'barChart')
            }
            if (data.value.graficos?.arrecadacao_por_tributo?.length) {
                criarGraficoPizza(data.value.graficos.arrecadacao_por_tributo, 'pieChart');
            }
        })
        .catch((error) => {
            if (error.response && error.response.data) {
                alert(error.response.data.message)
            } else {
                alert(error)
            }
        });
}

onMounted(() => {
    getDataDashBoard()
})

function deletar(id) {

    if (confirm('Tem certeza que deseja deletar este registro?')) {
        axios.delete(`api/arrecadacoes/${id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                Accept: 'application/json'
            }
        })
            .then(() => {
                getDataDashBoard()
            })
            .catch((error) => {
                if (error.response && error.response.data) {
                    alert(error.response.data.message)
                } else {
                    alert(error)
                }
            });
    }
}

// trnaformar o mês em nome
const meses = {
    1: 'Janeiro',
    2: 'Fevereiro',
    3: 'Março',
    4: 'Abril',
    5: 'Maio',
    6: 'Junho',
    7: 'Julho',
    8: 'Agosto',
    9: 'Setembro',
    10: 'Outubro',
    11: 'Novembro',
    12: 'Dezembro'
};

const anos = [];

let anoAtual = new Date().getFullYear();

for (let i = anoAtual; i >= anoAtual - 6; i--) {
    anos.push(i);
}

function abrirModal(item) {
    dadoSelecionado.value = item;
    showModal.value = true;
    isEditing.value = true;
}

function novoRegistro() {
    dadoSelecionado.value = null;
    showModal.value = true;
    isEditing.value = false;
}

const barChartInstance = ref(null);
const pieChartInstance = ref(null);

function criarGraficoBarras(dados, canvasId) {
    // se já existe um gráfico de barras, destrói antes de criar outro
    if (barChartInstance.value) {
        try {
            barChartInstance.value.destroy();
        } catch (e) {
            console.warn('Erro ao destruir gráfico de barras:', e);
        }
    }

    const ctx = document.getElementById(canvasId);

    barChartInstance.value = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dados.map(row => meses[row.mes]),
            datasets: [{
                label: 'Arrecadação Mensal (R$)',
                data: dados.map(row => row.total),
                backgroundColor: '#3B82F6',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `R$ ${ctx.raw.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                    }
                }
            }
        }
    });
}

function criarGraficoPizza(dados, canvasId) {
    // se já existe um gráfico de pizza, destrói antes de criar outro
    if (pieChartInstance.value) {
        try {
            pieChartInstance.value.destroy();
        } catch (e) {
            console.warn('Erro ao destruir gráfico de pizza:', e);
        }
    }

    const ctx = document.getElementById(canvasId);

    pieChartInstance.value = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: dados.map(row => row.tributo),
            datasets: [{
                data: dados.map(row => row.total),
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.label}: R$ ${ctx.raw.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
                    }
                }
            }
        }
    });
}
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
            <form @submit.prevent="aplicarFiltros()"
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-10 w-full">
                <h3 class="text-lg font-semibold mb-1 text-gray-800">Filtros</h3>
                <p class="text-sm text-gray-500 mb-6">Selecione o período e os tributos para análise.</p>

                <div class="grid md:grid-cols-3 gap-6 items-end">
                    <!-- ano -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Ano</h4>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Início</label>
                                <input v-model="anoInicio" name="anoInicio" type="number"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" />
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Fim</label>
                                <input v-model="anoFim" name="anoFim" type="number"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" />
                            </div>
                        </div>
                    </div>

                    <!-- mês -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Mês</h4>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Início</label>
                                <select v-model="mesInicio"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option v-for="(mes, index) in meses" :key="index" :value="index">
                                        {{ mes }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 block mb-1">Fim</label>
                                <select v-model="mesFim"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                                    <option v-for="(mes, index) in meses" :key="index" :value="index">
                                        {{ mes }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- tributos -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Tributos</h4>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" v-model="tributos" value="IPTU"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400" />
                                <span class="text-sm text-gray-700">IPTU</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" v-model="tributos" value="ISS"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400" />
                                <span class="text-sm text-gray-700">ISS</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" v-model="tributos" value="ITBI"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-400" />
                                <span class="text-sm text-gray-700">ITBI</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- botões -->
                <div class="mt-6">
                    <button type="submit"
                        class="px-4 py-2 mr-3 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-md shadow-sm transition">
                        Aplicar
                    </button>
                    <button type="button" @click="limparFiltros"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">
                        Limpar
                    </button>
                </div>
            </form>
            
            <!-- Total Arrecadado -->
            <div class="bg-blue-600 text-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold">Total Arrecadado</h3>
                <div class="text-4xl font-bold">R$ {{ (data.resumo?.total_arrecadado ?? 0).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2, maximumFractionDigits: 2
                }) }}
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold mb-1">Arrecadação por Mês</h4>
                    <p class="text-sm text-gray-500 mb-4">Arrecadação dos últimos 6 meses registrados</p>
                    <div class="h-60">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h4 class="font-semibold mb-1">Distribuição por Tributo</h4>
                    <p class="text-sm text-gray-500 mb-4">Participação de cada tributo no total</p>
                    <div class="h-60 mt-8 flex items-center justify-center">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Alerta de Administrador -->
            <div v-if="isLogado" class="m-8 p-4 border-l-4 border-blue-600 bg-blue-50 rounded-md shadow-sm">
                <h2 class="text-2xl font-semibold text-blue-800 mb-1">Acesso Administrativo</h2>
                <p class="text-gray-700">
                    Você está autenticado como <span class="font-semibold">Administrador</span> e possui permissão
                    para
                    gerenciar os registros de arrecadações.
                </p>
            </div>

            <!-- Botão para cadastrar nova arrecadação -->
            <div v-if="isLogado" class="flex justify-end mt-8">
                <button @click="novoRegistro"
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
                                <th class="text-center py-3 px-4">Ano</th>
                                <th class="text-center py-3 px-4">Valor</th>
                                <th v-if="isLogado" class="text-center py-3 px-4">Açoes</th>
                            </tr>
                        </thead>
                        <tbody v-if="data.dados?.arrecadacoes" v-for="dado in data.dados.arrecadacoes">
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ dado.tributo }}</td>
                                <td class="py-3 px-4">{{ meses[dado.mes] }}</td>
                                <td class="py-3 px-4 text-center">{{ dado.ano }}</td>
                                <td class="py-3 px-4 text-center font-semibold">R$ {{
                                    (dado.valor).toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }) }}</td>
                                <td class="py-3 px-4 ">
                                    <div v-if="isLogado" class="flex gap-2 justify-center">
                                        <div @click="abrirModal(dado)"
                                            class="border border-3 p-2 rounded-md cursor-pointer hover:bg-green-700">
                                            <Pencil class="w-4 h-4" />
                                        </div>

                                        <div @click="deletar(dado.id)"
                                            class="bg-red-500 p-2 rounded-md cursor-pointer hover:bg-red-600">
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

    <!-- Modal -->
    <Modal v-if="showModal" :data="dadoSelecionado" :showModal="showModal" @close="showModal = false"
        :isEditing="isEditing" :getDataDashBoard="getDataDashBoard" />

</template>