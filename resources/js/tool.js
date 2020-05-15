import CustomIndexToolbar from './components/CustomIndexToolbar'
import DetachedActionsIndex from './components/DetachedActionsIndex'
import CustomDetailToolbar from './components/CustomDetailToolbar'
import DetachedActionsDetail from './components/DetachedActionsDetail'

Nova.booting((Vue, router) => {
    Vue.component('custom-index-toolbar', CustomIndexToolbar)
    Vue.component('detached-actions-index', DetachedActionsIndex)
    Vue.component('custom-detail-toolbar', CustomDetailToolbar)
    Vue.component('detached-actions-detail', DetachedActionsDetail)
})