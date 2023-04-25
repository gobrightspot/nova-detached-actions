<template>
  <div class="flex justify-end items-center mr-3 ml-1">
    <invisible-actions-dropdown
      class="mr-2"
      v-if="shouldShowInvisibleActions"
      :actions="invisibleActions"
      :show-arrow="showInvisibleActionsArrow"
      :icon-type="invisibleActionsIcon"
      @dropdown-link-click="handleClick"
    ></invisible-actions-dropdown>

    <template v-for="action in visibleActions">
      <action-button
          class="mr-3"
          v-if="shouldShowActions || action.standalone"
          :key="action.uriKey"
          :action="action"
          @action-button-clicked="handleClick"
      ></action-button>
    </template>

    <!-- Confirm Action Modal -->
    <component
      v-if="confirmActionModalOpened"
      class="text-left"
      :show="confirmActionModalOpened"
      :is="selectedAction.component"
      :working="working"
      :selected-resources="selectedResources"
      :resource-name="resourceName"
      :action="selectedAction"
      :errors="errors"
      @confirm="executeAction"
      @close="closeConfirmationModal"
    />

    <component
      :is="actionResponseData.modal"
      @close="closeActionResponseModal"
      v-if="showActionResponseModal"
      :show="showActionResponseModal"
      :data="actionResponseData"
    />
  </div>
</template>
<script>
import DetachedAction from "../mixins/DetachedAction";

export default {
  mixins: [DetachedAction],

  props: ['shouldShowActions', 'resourceName', 'resourceId', 'actions', 'endpoint', 'actionQueryString', 'selectedResources'],

  watch: {
    actions(newActions, oldActions) {
      this.actionsList = newActions.filter((action) => action.hasOwnProperty('detachedAction'));
    },
  },

  computed: {
    currentSearch() {
      return this.actionQueryString.currentSearch
    },

    encodedFilters() {
      return this.actionQueryString.encodedFilters
    },

    currentTrashed() {
      return this.actionQueryString.currentTrashed
    },

    viaResource() {
      return this.actionQueryString.viaResource
    },

    viaResourceId() {
      return this.actionQueryString.viaResourceId
    },

    viaRelationship() {
      return this.actionQueryString.viaRelationship
    },

    actionsForSelect() {
      return [
        ...this.visibleActions.map(a => ({
          value: a.uriKey,
          label: a.name,
        })),
      ]
    },
  },

  created() {
    this.actionsList = this.actions;
  }
};
</script>
