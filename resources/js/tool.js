import CustomIndexToolbar from './components/CustomIndexToolbar'
import CustomDetailToolbar from './components/CustomDetailToolbar'

Nova.booting((Vue, router) => {
    Vue.component('custom-index-toolbar', CustomIndexToolbar);
    Vue.component('custom-detail-toolbar', CustomDetailToolbar);
})