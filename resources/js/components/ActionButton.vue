<template>
    <button
        :data-testid="actionId"
        :dusk="duskId"
        @click.prevent="click"
        :title="__(action.label)"
        class="flex-shrink-0 shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-3 h-9 text-sm flex-shrink-0"
        :class="action.classes"
    >
        <span class="mr-1">
          <component
            :is="`heroicons-outline-${action.icon}`"
            height="24"
            width="24"
          />
        </span>
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
