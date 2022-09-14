<template>
  <Dropdown>
    <DropdownTrigger
      :show-arrow="showArrow"
      class="rounded hover:bg-gray-200 dark:hover:bg-gray-800 focus:outline-none focus:ring"
      :active="open"
    >
      <BasicButton v-if="iconType" component="span">
        <Icon :solid="true" :type="iconType" />
      </BasicButton>
    </DropdownTrigger>

    <template #menu>
      <DropdownMenu width="auto" class="px-1">
        <ScrollWrap
          :height="250"
          class="divide-y divide-gray-100 dark:divide-gray-800 divide-solid"
        >
          <ul class="list-reset">
            <li>
              <action-link
                :action="action"
                @action-link-clicked="click(action)"
                v-for="action in actions"
                :key="action.uriKey"
              ></action-link>
            </li>
          </ul>
        </ScrollWrap>
      </DropdownMenu>
    </template>
  </Dropdown>
</template>
<script>
export default {
  props: {
    actions: { type: Array, required: true },
    showArrow: { type: Boolean, required: false, default: false },
    iconType: { type: String, required: false, default: "hero-more-horiz" },
  },

  data() {
    return {
      open: false,
    };
  },

  methods: {
    click(action) {
      this.$emit("dropdown-link-click", action);
    },
  },
};
</script>
