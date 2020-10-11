<template>
    <a href="#"
       :data-testid="actionId"
       :dusk="duskId"
       @click.prevent="click"
       :title="__(action.label)"
       class="block no-underline text-90 hover:bg-30 py-3 px-6 detached-action-link flex items-center"
    >
        <icon v-if="action.icon" :type="action.icon" width="20" height="20" view-box="0 0 24 24" :class="action.iconClasses" />
        <span>{{ __(action.label) }}</span>
    </a>
</template>
<script>
export default {
    props: {
        action: {type: Object, required: true}
    },

    methods: {
        click() {
            this.$emit('action-link-clicked', this.action)
        }
    },

    computed: {
        actionId() {
            return `import-invisible-action-${this.actionCode}`
        },

        duskId() {
            return `run-action-link-${this.actionCode}`
        },

        actionCode() {
            return this.action.name.toLowerCase().replace(' ', '-')
        }
    }
}
</script>
