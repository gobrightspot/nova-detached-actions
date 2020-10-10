let mix = require('laravel-mix')

mix.setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .webpackConfig({
    resolve: {
      alias: {
        'laravel-nova': path.resolve(__dirname, './node_modules/laravel-nova/src/index.js'),
        '@nova': path.resolve(__dirname, './vendor/laravel/nova/resources/js/')
      }
    }
  })
