<template>
    <div class="flex w-full justify-end items-center mr-3 ml-1">
        <invisible-actions
            v-if="shouldShowInvisibleActions"
            :actions="invisibleActions.reverse()"
            :show-arrow="showInvisibleActionsArrow"
            :icon-type="invisibleActionsIcon"
            @dropdown-link-click="handleClick"
        ></invisible-actions>
        <action-button
            v-for="action in visibleActions.reverse()"
            :key="action.uriKey"
            :action="action"
            @action-button-clicked="handleClick"></action-button>
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
  import ActionLink from "./ActionButton";

  export default {
      components: {ActionLink},
      mixins: [DetachedAction],

      methods: {
          handleResponse(response) {
              this.actionsList = _.filter(response.data.actions, action => action.showOnIndexToolbar)
          }
      }
  }
</script>
