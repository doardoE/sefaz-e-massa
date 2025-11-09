import axios from "axios";
import { ref } from "vue";

axios.defaults.baseURL = 'http://127.0.0.1:8000/' // colocar nas variáveis de ambiente
axios.defaults.headers['Content-Type'] = 'application/json';
// 'Accept': 'application/json',

const token = ref(localStorage.getItem('token'));
if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
} else {
    delete axios.defaults.headers.common['Authorization'];
}

export default axios;