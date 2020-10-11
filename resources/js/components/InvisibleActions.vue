<template>
    <dropdown placement="bottom-end" dusk="detached-actions-invisible-buttons-dropdown">
        <dropdown-trigger :show-arrow="showArrow" class="bg-30 px-3 border-2 border-30 rounded" :active="open">
            <icon :type="iconType" width="20" height="20" view-box="0 0 24 24" />
        </dropdown-trigger>
        <dropdown-menu slot="menu" width="250">
            <ul class="list-reset">
                <li>
                    <action-link
                        :action="action"
                        @action-link-clicked="click(action)"
                        v-for="action in actions"
                        :key="action.uriKey"
                    ></action-link>
                </li>
            </ul>
        </dropdown-menu>
    </dropdown>
</template>
<script>
export default {
    props: {
        actions: {type: Array, required: true},
        showArrow: {type: Boolean, required: false, default: false},
        iconType: {type: String, required: false, default: 'hero-more-horiz'}
    },

    data() {
        return {
            open: false
        }
    },

    methods: {
        click(action) {
            this.$emit('dropdown-link-click', action)
        }
    }
}
</script>