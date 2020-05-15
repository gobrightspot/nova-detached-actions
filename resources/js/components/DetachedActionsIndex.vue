<template>
    <div>
        <button
            :data-testid="`detached-index-action-confirm-${action.uriKey}`"
            :dusk="`run-detached-index-action-button-${action.uriKey}`"
            @click.prevent="openConfirmationModal(action)"
            class="btn btn-default btn-detached-action btn-detached-index-action"
            :title="action.label"
            v-for="action in detachedActions"
            :key="action.uriKey"
        >
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