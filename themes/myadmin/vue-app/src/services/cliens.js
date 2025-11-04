import axios from 'axios'

const API = axios.create({
  baseURL: mydata.baseUrl,
  headers: {
    Accept: 'application/json',
  },
})

// EXEMPLE services :
export function createClient(data) {
    return API.post("/clients", data)
  }
  
  export function updateClient(id, data) {
    return API.put(`/clients/${id}`, data)
  }
  
  export function deleteClient(id) {
    return API.delete(`/clients/${id}`)
  }
  
  export function fetchClients() {
    return API.get("/clients")
  }
// /api/v2/[entity]/[content_type]
