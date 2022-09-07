<template>
  <div class="flex justify-end items-center mr-3 ml-1">
    <invisible-actions
      class="mr-2"
      v-if="shouldShowInvisibleActions"
      :actions="invisibleActions.reverse()"
      :show-arrow="showInvisibleActionsArrow"
      :icon-type="invisibleActionsIcon"
      @dropdown-link-click="handleClick"
    ></invisible-actions>

    <action-button
      class="mr-3"
      v-for="action in visibleActions"
      :key="action.uriKey"
      :action="action"
      @action-button-clicked="handleClick"
    ></action-button>

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
import ActionLink from "./ActionButton";

export default {
  components: { ActionLink },
  mixins: [DetachedAction],

  props: ['resourceName', 'selectedResources'],
  methods: {
    handleResponse(response) {
      this.actionsList = _.filter(
        response.data.actions,
        (action) => action.showOnIndexToolbar
      );
    },
  },
};
</script>
