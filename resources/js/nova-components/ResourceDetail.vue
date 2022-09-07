<template>
  <LoadingView :loading="initialLoading">
    <template v-if="shouldOverrideMeta && resourceInformation && title">
      <Head
        :title="
          __(':resource Details: :title', {
            resource: resourceInformation.singularLabel,
            title: title,
          })
        "
      />
    </template>

    <div v-if="shouldShowCards && hasDetailOnlyCards">
      <Cards
        v-if="cards.length > 0"
        :cards="cards"
        :only-on-detail="true"
        :resource="resource"
        :resource-id="resourceId"
        :resource-name="resourceName"
      />
    </div>

    <!-- Resource Detail -->
    <div
      :class="{
        'mt-6': shouldShowCards && hasDetailOnlyCards && cards.length > 0,
      }"
      :dusk="resourceName + '-detail-component'"
    >
      <component
        :is="resolveComponentName(panel)"
        v-for="panel in panels"
        :key="panel.id"
        :panel="panel"
        :resource="resource"
        :resource-id="resourceId"
        :resource-name="resourceName"
        class="mb-8"
      >
        <div v-if="panel.showToolbar" class="md:flex items-center mb-3">
          <div class="flex flex-auto truncate items-center">
            <Heading :level="1" v-text="panel.name" />
            <Badge
              v-if="resource.softDeleted"
              :label="__('Soft Deleted')"
              class="bg-red-100 text-red-500 dark:bg-red-400 dark:text-red-900 rounded px-2 py-0.5 ml-3"
            />
          </div>

          <div class="ml-auto flex items-center">
            <DetailActions
                :resource-id="resource.id.value"
                :resource-name="resourceName"
                @actionExecuted="getResources"
            ></DetailActions>

            <!-- Actions Menu -->
            <DetailActionDropdown
              v-if="resource"
              :resource="resource"
              :actions="actions"
              :via-resource="viaResource"
              :via-resource-id="viaResourceId"
              :via-relationship="viaRelationship"
              :resource-name="resourceName"
              class="mt-1 md:mt-0 md:ml-2 md:mr-2"
              @actionExecuted="actionExecuted"
              @resource-deleted="getResource"
              @resource-restored="getResource"
            />

            <Link
              v-if="resource.authorizedToUpdate"
              v-tooltip="{
                placement: 'bottom',
                distance: 10,
                skidding: 0,
                content: __('Edit'),
              }"
              :href="$url(`/resources/${resourceName}/${resourceId}/edit`)"
              class="rounded hover:bg-gray-200 dark:hover:bg-gray-800 focus:outline-none focus:ring"
              data-testid="edit-resource"
              dusk="edit-resource-button"
              tabindex="1"
            >
              <BasicButton component="span">
                <Icon type="pencil-alt" />
              </BasicButton>
            </Link>
          </div>
        </div>
      </component>
    </div>
  </LoadingView>
</template>

<script>
import Detail from "@/views/Detail";

export default {
  extends: Detail,
};
</script>
