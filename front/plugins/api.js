import axios from 'axios'

let API_URL = 'http://localhost:8888/api';

//hot fix for docker
if (process.server) {
    API_URL = 'http://nginx/api'
}

const headers = {
    'accept': 'application/json'
}

class API {
    getRateList() {
        return axios.get(`${API_URL}/rates`, {headers: headers});
    }

    getRate(id) {
        return axios.get(`${API_URL}/rates/${id}`, {headers: headers});
    }

    updateRate(rate) {
        return axios.put(`${API_URL}/rates/${rate.id}`, rate, {headers: headers});
    }

    deleteRate(id) {
        return axios.delete(`${API_URL}/rates/${id}`, {headers: headers});
    }
}

export default new API()

