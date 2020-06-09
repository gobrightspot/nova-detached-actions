<template>
    <div class="flex w-full justify-end items-center mx-3">
        <button
            data-testid="import-action-confirm"
            dusk="run-import-action-button"
            @click.prevent="determineActionStrategy(action)"
            :title="__(action.label)"
            class="btn btn-default ml-3 btn-detached-action btn-detached-index-action"
            :class="action.classes"
            v-for="action in detachedActions"
            :key="action.uriKey">
            <span>{{ __(action.label) }}</span>
        </button>
        <transition name="fade">
            <component
                :is="selectedAction.component"
                :working="working"
                v-if="confirmActionModalOpened"
                :selected-resources="selectedResources"
                :resource-name="resourceName"
                :action="selectedAction"
                :errors="errors"
                @confirm="executeAction"
                @close="confirmActionModalOpened = false"/>
        </transition>
    </div>
</template>
<script>
  import DetachedAction from "../mixins/DetachedAction";

  export default {
    mixins: [DetachedAction],

    methods: {
      handleResponse(response) {
        this.actionsList = _.filter(response.data.actions, action => action.showOnIndexToolbar)
      }
    }
  }
</script>
