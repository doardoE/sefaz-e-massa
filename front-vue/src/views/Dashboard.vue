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

function getDataDashBoard() {
    // puxando os dados da API
    axios.get('api/arrecadacoes/dashboard')
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
    0: '-',
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

            <!-- Total Arrecadado -->
            <div class="bg-blue-600 text-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold">Total Arrecadado em {{ new Date().getFullYear() }}</h3>
                <div class="text-4xl font-bold">R$ {{ (data.resumo?.total_arrecadado ?? 0).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
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
                    Você está autenticado como <span class="font-semibold">Administrador</span> e possui permissão para
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
                                <td class="py-3 px-4 text-center font-semibold">R$ {{ (dado.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</td>
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