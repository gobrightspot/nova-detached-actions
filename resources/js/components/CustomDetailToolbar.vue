<template>
    <div class="flex w-full justify-end items-center">
        <button
            data-testid="import-action-confirm"
            dusk="run-import-action-button"
            @click.prevent="openConfirmationModal(action)"
            class="btn btn-default btn-detached-action btn-detached-detail-action"
            :title="action.label"
            v-for="action in detachedActions" 
            :key="action.uriKey"
        >
            <span>{{ __(action.label) }}</span>
        </button>

        <!-- Action Confirmation Modal -->
        <!-- <portal to="modals"> -->
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
                @close="confirmActionModalOpened = false"
            />
        </transition>
        <!-- </portal> -->
    </div>
</template>

<script>
  import DetachedAction from "../mixins/DetachedAction";

  export default {
    mixins: [DetachedAction],

    methods: {
      handleResponse(response) {
        this.actionsList = _.filter(response.data.actions, action => action.showOnDetailToolbar)
      }
    }
  }
</script>
