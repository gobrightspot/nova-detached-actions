<template>
  <LoadingView
    :loading="initialLoading"
    :dusk="resourceName + '-index-component'"
    :data-relationship="viaRelationship"
  >
    <template v-if="shouldOverrideMeta && resourceInformation">
      <Head :title="__(`${resourceInformation.label}`)" />
    </template>

    <Cards
      v-if="shouldShowCards"
      :cards="cards"
      :resource-name="resourceName"
    />

    <Heading
      :level="1"
      class="mb-3 flex items-center"
      :class="{ 'mt-6': shouldShowCards && cards.length > 0 }"
    >
      <span v-html="headingTitle" />
      <button
        v-if="!loading && viaRelationship"
        @click="handleCollapsableChange"
        class="rounded border border-transparent h-6 w-6 ml-1 inline-flex items-center justify-center focus:outline-none focus:ring ring-primary-200"
        :aria-label="__('Toggle Collapsed')"
        :aria-expanded="shouldBeCollapsed === false ? 'true' : 'false'"
      >
        <CollapseButton :collapsed="shouldBeCollapsed" />
      </button>
    </Heading>

    <template v-if="!shouldBeCollapsed">
      <div class="flex">
        <IndexSearchInput
          :class="{ 'mb-6': !viaResource }"
          v-if="
            resourceInformation && resourceInformation.searchable && !viaHasOne
          "
          :searchable="
            resourceInformation && resourceInformation.searchable && !viaHasOne
          "
          v-model:keyword="search"
          @update:keyword="search = $event"
        />

        <div class="w-full flex items-center" :class="{ 'mb-6': !viaResource }">
          <IndexActions :resourceName="resourceName">
          </IndexActions>
          <!-- Create / Attach Button -->
          <CreateResourceButton
            :label="createButtonLabel"
            :singular-name="singularName"
            :resource-name="resourceName"
            :via-resource="viaResource"
            :via-resource-id="viaResourceId"
            :via-relationship="viaRelationship"
            :relationship-type="relationshipType"
            :authorized-to-create="authorizedToCreate && !resourceIsFull"
            :authorized-to-relate="authorizedToRelate"
            class="flex-shrink-0 ml-auto"
            :class="{ 'mb-6': viaResource }"
          />
        </div>
      </div>

      <Card>
        <ResourceTableToolbar
          :action-query-string="actionQueryString"
          :all-matching-resource-count="allMatchingResourceCount"
          :authorized-to-delete-any-resources="authorizedToDeleteAnyResources"
          :authorized-to-delete-selected-resources="
            authorizedToDeleteSelectedResources
          "
          :authorized-to-force-delete-any-resources="
            authorizedToForceDeleteAnyResources
          "
          :authorized-to-force-delete-selected-resources="
            authorizedToForceDeleteSelectedResources
          "
          :authorized-to-restore-any-resources="authorizedToRestoreAnyResources"
          :authorized-to-restore-selected-resources="
            authorizedToRestoreSelectedResources
          "
          :available-actions="availableActions"
          :clear-selected-filters="clearSelectedFilters"
          :close-delete-modal="closeDeleteModal"
          :currently-polling="currentlyPolling"
          :delete-all-matching-resources="deleteAllMatchingResources"
          :delete-selected-resources="deleteSelectedResources"
          :filter-changed="filterChanged"
          :force-delete-all-matching-resources="forceDeleteAllMatchingResources"
          :force-delete-selected-resources="forceDeleteSelectedResources"
          :get-resources="getResources"
          :has-filters="hasFilters"
          :have-standalone-actions="haveStandaloneActions"
          :lenses="lenses"
          :per-page-options="perPageOptions"
          :per-page="perPage"
          :pivot-actions="pivotActions"
          :pivot-name="pivotName"
          :resources="resources"
          :resource-information="resourceInformation"
          :resource-name="resourceName"
          :restore-all-matching-resources="restoreAllMatchingResources"
          :restore-selected-resources="restoreSelectedResources"
          :select-all-matching-checked="selectAllMatchingResources"
          :selected-resources="selectedResources"
          :selected-resources-for-action-selector="
            selectedResourcesForActionSelector
          "
          :should-show-action-selector="shouldShowActionSelector"
          :should-show-check-boxes="shouldShowCheckBoxes"
          :should-show-delete-menu="shouldShowDeleteMenu"
          :should-show-polling-toggle="shouldShowPollingToggle"
          :soft-deletes="softDeletes"
          @start-polling="startPolling"
          @stop-polling="stopPolling"
          :toggle-select-all-matching="toggleSelectAllMatching"
          :toggle-select-all="toggleSelectAll"
          :trashed-changed="trashedChanged"
          :trashed-parameter="trashedParameter"
          :trashed="trashed"
          :update-per-page-changed="updatePerPageChanged"
          :via-has-one="viaHasOne"
          :via-many-to-many="viaManyToMany"
          :via-resource="viaResource"
        />

        <LoadingView :loading="loading">
          <IndexErrorDialog
            v-if="resourceResponseError != null"
            :resource="resourceInformation"
            @click="getResources"
          />

          <template v-else>
            <IndexEmptyDialog
              v-if="!resources.length"
              :create-button-label="createButtonLabel"
              :singular-name="singularName"
              :resource-name="resourceName"
              :via-resource="viaResource"
              :via-resource-id="viaResourceId"
              :via-relationship="viaRelationship"
              :relationship-type="relationshipType"
              :authorized-to-create="authorizedToCreate"
              :authorized-to-relate="authorizedToRelate"
            />

            <ResourceTable
              :authorized-to-relate="authorizedToRelate"
              :resource-name="resourceName"
              :resources="resources"
              :singular-name="singularName"
              :selected-resources="selectedResources"
              :selected-resource-ids="selectedResourceIds"
              :actions-are-available="allActions.length > 0"
              :should-show-checkboxes="shouldShowCheckBoxes"
              :via-resource="viaResource"
              :via-resource-id="viaResourceId"
              :via-relationship="viaRelationship"
              :relationship-type="relationshipType"
              :update-selection-status="updateSelectionStatus"
              :sortable="sortable"
              @order="orderByField"
              @reset-order-by="resetOrderBy"
              @delete="deleteResources"
              @restore="restoreResources"
              @actionExecuted="getResources"
              ref="resourceTable"
            />

            <ResourcePagination
              :pagination-component="paginationComponent"
              :should-show-pagination="shouldShowPagination"
              :has-next-page="hasNextPage"
              :has-previous-page="hasPreviousPage"
              :load-more="loadMore"
              :select-page="selectPage"
              :total-pages="totalPages"
              :current-page="currentPage"
              :per-page="perPage"
              :resource-count-label="resourceCountLabel"
              :current-resource-count="currentResourceCount"
              :all-matching-resource-count="allMatchingResourceCount"
            />
          </template>
        </LoadingView>
      </Card>
    </template>
  </LoadingView>
</template>

<script>
import Index from "@/views/Index";

export default {
  extends: Index,
};
</script>
