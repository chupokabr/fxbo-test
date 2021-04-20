<template>
  <div class="container mx-auto">
    <div class="mt-10 sm:mt-0">
      <div class="mt-5 md:mt-0">
        <form action="#" method="POST">
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
              <div class="grid grid-cols-6 gap-6">


                <div class="col-span-6">
                  <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                  <select id="from" name="from" v-model="from"
                          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <template v-for="currency in currencies">
                      <option :value="currency">{{ currency }}</option>
                    </template>
                  </select>
                </div>

                <div class="col-span-6">
                  <label for="to" class="block text-sm font-medium text-gray-700">To</label>
                  <select id="to" name="to" v-model="to"
                          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <template v-for="currency in currencies">
                      <option :value="currency">{{ currency }}</option>
                    </template>
                  </select>
                </div>

                <div class="col-span-6">
                  <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                  <input type="text" name="amount" id="amount" v-model="amount"
                         class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
              </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6">
              {{ this.convertResult }}
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>
<script>
import API from '~/plugins/api'

export default {
  data() {
    return {
      from: '',
      to: '',
      amount: 1,
      result: '',
      errors: '',
      rates: []
    }
  },
  async created() {
    await this.loadRates()
  },
  computed: {
    currencies() {
      return [...new Set(this.rates.reduce((res, item) => {
        res.push(item.base)
        res.push(item.quote)
        return res
      }, []))]
    },
    convertResult() {
      if (this.from && this.to && this.amount > 0) {
        let price = this.getPairPrice(this.from, this.to)
        if (price > 0) {
          let formatPrice = this.formatPrice(this.amount * price);
          return `${this.amount} ${this.from} = ${formatPrice} ${this.to}`
        } else {
          return `No available convert ${this.from}/${this.to}`
        }
      } else {
        return 'Let`s start!'
      }
    }
  },

  methods: {
    loadRates() {
      return API.getRateList().then(response => this.rates = response.data)
    },
    formatPrice(price) {
      if (price === "-") {
        return price;
      }
      return price > 1 ? price.toFixed(2) : price.toPrecision(2);
    },
    getPairPrice(from, to) {
      let price = this.getDirectPairPrice(from, to);
      if (price > 0) return price;

      let transferChain = this.findTransferPair(from, to);
      if (transferChain.length > 0) {
        transferChain.push(from)
        transferChain.reverse()
        return this.getPriceByChain(transferChain)
      }

      return -1
    },
    getDirectPairPrice(from, to) {
      if (from === to) return 1

      let pair = this.rates.filter(rate => (rate.base === from && rate.quote === to) || (rate.base === to && rate.quote === from))
      if (pair.length > 0) {
        if (pair[0].base === this.from) {
          return pair[0].price
        } else {
          return 1 / pair[0].price
        }
      } else {
        return -1
      }
    },
    getPriceByChain(chain) {
      let base = chain.shift();
      return chain.reduce((price, currency) => {
        if (price) {
          price = price * this.getDirectPairPrice(base, currency)
        } else {
          price = this.getDirectPairPrice(base, currency)
        }
        base = currency

        return price
      }, 0)
    },
    findTransferPair(from, to, chain, checkedChain) {
      if (!checkedChain) checkedChain = []
      if (!chain) chain = []
      checkedChain.push(from)

      let fromPairs = this.getTransferPair(from)
      fromPairs = fromPairs.filter(value => !checkedChain.includes(value))

      if (fromPairs.length === 0) return false

      if (fromPairs.includes(to)) {
        chain.push(to)
        return chain
      }
      for (let i = 0; i < fromPairs.length; i++) {
        let item = fromPairs[i]
        let res = this.findTransferPair(item, to, chain, checkedChain)
        if (res) {
          chain.push(item)
          return chain
        }
      }

      return false
    },
    getTransferPair(currency) {
      return [...new Set(this.rates.reduce((res, rate) => {
        if (rate.base === currency) {
          res.push(rate.quote)
        }
        if (rate.quote === currency) {
          res.push(rate.base)
        }
        return res
      }, []))]
    },
  }
}
</script>
