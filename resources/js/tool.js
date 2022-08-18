Nova.booting((app, store) => {
  app.component(
    "ActionButton",
    require("./components/ActionButton.vue").default
  );
  app.component("ActionLink", require("./components/ActionLink.vue").default);
  app.component(
    "InvisibleActions",
    require("./components/InvisibleActions.vue").default
  );
  app.component(
    "DetailActions",
    require("./components/DetailActions.vue").default
  );
  app.component(
    "IndexActions",
    require("./components/IndexActions.vue").default
  );

  app.component(
    "ResourceDetail",
    require("./nova-components/ResourceDetail.vue").default
  );

  app.component(
    "ResourceIndex",
    require("./nova-components/ResourceIndex.vue").default
  );
});
