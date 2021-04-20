import axios from 'axios'

const API_URL = 'http://localhost:8888/api';

axios.headers = {
    'accept': 'application/json'
}

export default class API {
    getRateList() {
        return axios.get(`${API_URL}/rates`);
    }

    getRate(id) {
        return axios.get(`${API_URL}/rates/${id}`);
    }

    updateRate(rate) {
        return axios.put(`${API_URL}/rates/${id}`, rate);
    }

    deleteRate(id) {
        return axios.delete(`${API_URL}/rates/${id}`);
    }
}


