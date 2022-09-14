Nova.booting((app, store) => {
  app.component("ActionLink", require("./components/ActionLink.vue").default);
  app.component("InvisibleActionsDropdown", require("./components/InvisibleActionsDropdown.vue").default);
  app.component("ActionButton", require("./components/ActionButton.vue").default);
  app.component("ActionButtonGroup", require("./components/ActionButtonGroup.vue").default);

  app.component("ResourceDetail", require("./nova-components/ResourceDetail.vue").default);
  app.component("ResourceTableToolbar", require("./nova-components/ResourceTableToolbar.vue").default);
});
