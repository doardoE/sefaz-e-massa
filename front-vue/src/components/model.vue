<script setup>
import Dashboard from '@/views/Dashboard.vue';
import axios from 'axios';
import { ref, computed, watch } from 'vue';

const props = defineProps({
  data: {
    type: Object,
    required: false,
    default: null
  },
  showModal: {
    type: Boolean,
    required: true
  },
  isEditing: {
    type: Boolean,
    required: true
  },
  getDataDashBoard: Function,
})

const emit = defineEmits(['close'])

const id = ref('')
const tributo = ref('');
const mes = ref('');
const ano = ref('');
const valor = ref('');

const tributos = ['IPTU', 'ISS', 'ITBI'];

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

// Sempre que abrir o modal com um dado, preenche os campos
watch(
  () => props.showModal,
  (aberto) => {
    if (!aberto) return

    if (props.isEditing && props.data) {
      tributo.value = props.data.tributo || ''
      mes.value = props.data.mes || ''
      ano.value = props.data.ano || ''
      valor.value = props.data.valor || ''
    } else {
      id.value = ''
      tributo.value = ''
      mes.value = ''
      ano.value = ''
      valor.value = ''
    }
  },
  { immediate: true }
)

// método para criação
function createArrecadacao() {
  axios.post('api/arrecadacoes', {
    tributo: tributo.value,
    mes: Number(mes.value),
    ano: Number(ano.value),
    valor: parseFloat(valor.value)
  },
    {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        Accept: 'application/json'
      }
    })
    .then((resposta) => {
      alert(resposta.data.message)
      props.getDataDashBoard()
      emit('close')

      console.log(resposta)
      id.value = ''
      tributo.value = ''
      mes.value = ''
      ano.value = ''
      valor.value = ''
    })
    .catch((error) => {
      if (error.response && error.response.data) {
        alert(error.response.data.message);
      } else {
        alert('Erro inesperado');
      }
    });
}

// método para edição
function updateArrecadacao(id) {
  axios.put(`api/arrecadacoes/${id}`, {
    id:id,
    tributo: tributo.value,
    mes: Number(mes.value),
    ano: Number(ano.value),
    valor: parseFloat(valor.value),
  },
    {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        Accept: 'application/json'
      }
    })
    .then((resposta) => {
      alert(resposta.data.message)
      props.getDataDashBoard()
      emit('close')

      console.log(resposta)
      tributo.value = ''
      mes.value = ''
      ano.value = ''
      valor.value = ''
    })
    .catch((error) => {
      if (error.response && error.response.data) {
        alert(error.response.data.message);
      } else {
        alert('Erro inesperado');
      }
    });
}

</script>

<template>
  <div v-if="showModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative">
      <h2 class="text-lg font-semibold mb-4">
        {{ props.isEditing ? 'Editar Arrecadação' : 'Nova Arrecadação' }}
      </h2>

      <!-- Tributo -->
      <form class="space-y-3">
        <div>
          <label class="block text-sm mb-1">Tributo</label>
          <select v-model="tributo" required class="w-full border rounded-lg p-2">
            <option disabled value="">Selecione...</option>
            <option v-for="tributo in tributos">{{ tributo }}</option>
          </select>
        </div>

        <!-- Mês -->
        <div>
          <label class="block text-sm mb-1">Mês</label>
          <select v-model="mes" required class="w-full border rounded-lg p-2">
            <option disabled value="">Selecione...</option>
            <option v-for="(nome, numero) in meses" :key="numero" :value="numero">
              {{ nome }}
            </option>

          </select>
        </div>

        <!-- Ano -->
        <div>
          <label class="block text-sm mb-1">Ano</label>
          <input v-model="ano" type="number" step="0" required class="w-full border rounded-lg p-2"></input>
        </div>

        <!-- Valor -->
        <div>
          <label class="block text-sm mb-1">Valor</label>
          <input v-model="valor" type="number" step="0.01" required class="w-full border rounded-lg p-2" />
        </div>

        <div class="flex justify-end space-x-2 pt-4">
          <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
            Cancelar
          </button>

          <button v-if="!props.isEditing" type="button" @click="createArrecadacao()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Cadastrar
          </button>

          <button v-else type="button" @click="updateArrecadacao(props.data.id)"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Atualizar
          </button>

        </div>
      </form>
    </div>
  </div>
</template>