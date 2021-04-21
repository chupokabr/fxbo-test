<template>
  <div class="container mx-auto">
    <div class="flex flex-col">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Id
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Base
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Quote
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Provider
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Created
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Updated
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Action</span>
                </th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
              <template v-for="rate in rates">
                <tr v-bind:key="rate.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ rate.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <template v-if="selectedRate === rate">
                      <input
                          v-model="editBase"
                          type="text"
                          class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="">
                    </template>
                    <template v-else>
                      {{ rate.base }}
                    </template>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <template v-if="selectedRate === rate">
                      <input
                          v-model="editQuote"
                          type="text"
                          class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="">
                    </template>
                    <template v-else>
                      {{ rate.quote }}
                    </template>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <template v-if="selectedRate === rate">
                      <input
                          v-model="editPrice"
                          type="text"
                          class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="">
                    </template>
                    <template v-else>
                      {{ rate.price }}
                    </template>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <template v-if="selectedRate === rate">
                      <input
                          v-model="editProvider"
                          type="text"
                          class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="">
                    </template>
                    <template v-else>
                      {{ rate.provider }}
                    </template>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ rate.created }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ rate.updated }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <template v-if="selectedRate === rate">
                      <button
                          @click="handleUpdate()"
                          class="mr-5 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Save
                      </button>
                    </template>
                    <template v-else>
                      <button
                          @click="select(rate)"
                          class="mr-5 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit
                      </button>
                    </template>
                    <a
                        @click.stop="handleDelete(rate)"
                        href="#" class="text-indigo-600 hover:text-indigo-900">Delete</a>
                  </td>
                </tr>
              </template>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import API from '~/plugins/api'

export default {
  data() {
    return {
      rates: [],
      selectedRate: null,
      editBase: "",
      editQuote: "",
      editPrice: "",
      editProvider: "",
    }
  },
  async created() {
    await this.loadRates()
  },
  computed: {
    selectedRateData() {
      if (!this.selectedRate) return false
      if (!this.editBase) return false
      if (!this.editQuote) return false
      if (!this.editPrice || parseFloat(this.editPrice) < 0) return false
      if (!this.editProvider) return false

      let rateData = this.selectedRate
      rateData.base = this.editBase
      rateData.quote = this.editQuote
      rateData.price = this.editPrice
      rateData.provider = this.editProvider

      return rateData
    },
  },
  methods: {
    select(rate) {
      this.selectedRate = rate
      this.editBase = rate.base;
      this.editQuote = rate.quote;
      this.editPrice = rate.price;
      this.editProvider = rate.provider;
    },
    loadRates() {
      return API.getRateList().then(response => this.rates = response.data)
    },
    handleUpdate() {
      let rateData = this.selectedRateData
      this.selectedRate = null
      if (rateData) {
        this.rates = this.rates.map(item => {
          if (item === rateData) {
            return rateData
          } else {
            return item
          }
        })

        return API.updateRate(rateData).catch(error => {
          this.loadRates()
          console.log('Error', error.message)
        })
      }
    },
    handleDelete(rate) {
      this.rates = this.rates.filter(item => item !== rate)
      return API.deleteRate(rate.id).catch(error => {
        this.loadRates()
        console.log('Error', error.message)
      })
    },

  }
}
</script>
