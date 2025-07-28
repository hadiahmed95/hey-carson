const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true
})
module.exports = {
  chainWebpack: (config) => {
    const svgRule = config.module.rule('svg');

    svgRule.uses.clear();

    svgRule
        .use('vue-loader')
        .loader('vue-loader') // or `vue-loader-v16` if you are using a preview support of Vue 3 in Vue CLI
        .end()
        .use('vue-svg-loader')
        .loader('vue-svg-loader');
  },

  devServer: {
    proxy: "http://127.0.0.1:8000",
    compress: true,
    port: '80',
    allowedHosts: [
      '.shopexperts.com'
    ]
  }
};
