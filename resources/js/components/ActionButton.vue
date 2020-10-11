<template>
    <button
        :data-testid="actionId"
        :dusk="duskId"
        @click.prevent="click"
        :title="__(action.label)"
        class="btn btn-default ml-3 detached-action-button flex justify-center items-center"
        :class="action.classes"
    >
        <icon v-if="action.icon" :type="action.icon" width="20" height="20" view-box="0 0 24 24" :class="action.iconClasses" />
        <span>{{ __(action.label) }}</span>
    </button>
</template>
<script>
export default {
    props: {
        action: {type: Object, required: true}
    },

    methods: {
        click() {
            this.$emit('action-button-clicked', this.action)
        }
    },

    computed: {
        actionId() {
            return `import-action-${this.actionCode}`
        },

        duskId() {
            return `run-action-button-${this.actionCode}`
        },

        actionCode() {
            return this.action.name.toLowerCase().replace(' ', '-')
        }
    }
}
</script>
