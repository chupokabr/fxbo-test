module.exports = {
    modules: [
        '@nuxtjs/axios',
    ],
    plugins: ['~/plugins/api.js'],
    buildModules: ['@nuxtjs/tailwindcss'],
    tailwindcss: {
        jit: true
    }
}
