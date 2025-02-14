import axios from 'axios'
import { responseValidate, catchSupportId } from '@/js/utils/helper.js'

// set header X-CSRF-TOKEN
let token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

// set header X-Requested-With
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Accept'] = 'application/json'

// set middleware for axios
axios.interceptors.response.use(
  (response) => {
    catchSupportId(response)
    return response
  },
  (error) => Promise.reject(responseValidate(error)),
)

export function useAxios() {
  return axios
}
