<template>
  <div class="flex w-full justify-end items-center">
    <invisible-actions
      class="mr-2"
      v-if="shouldShowInvisibleActions"
      :actions="invisibleActions.reverse()"
      :show-arrow="showInvisibleActionsArrow"
      :icon-type="invisibleActionsIcon"
      @dropdown-link-click="handleClick"
    ></invisible-actions>

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

  props: ['shouldShowActions', 'resourceId', 'resourceName'],
  methods: {
    handleResponse(response) {
      this.actionsList = _.filter(
        response.data.actions,
        (action) => action.showOnDetailToolbar
      );
    },
  },
  computed: {
    selectedResources() {
      return [this.resourceId];
    },
  }
};
</script>
