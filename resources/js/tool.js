import ActionButton from './components/ActionButton'
import ActionLink from './components/ActionLink'
import InvisibleActions from './components/InvisibleActions'
import CustomIndexToolbar from './components/CustomIndexToolbar'
import CustomDetailToolbar from './components/CustomDetailToolbar'
import Heroicons from './components/Heroicons'

Nova.booting((Vue, router) => {
    for (let iconComponent in Heroicons) {
        Vue.component(iconComponent, Heroicons[iconComponent])
    }
    Vue.component('action-button', ActionButton)
    Vue.component('action-link', ActionLink)
    Vue.component('invisible-actions', InvisibleActions)
    Vue.component('custom-index-toolbar', CustomIndexToolbar)
    Vue.component('custom-detail-toolbar', CustomDetailToolbar)
})
