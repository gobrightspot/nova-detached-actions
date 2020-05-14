<template>
    <div class="flex w-full justify-end items-center mx-3">
        <button
            data-testid="import-action-confirm"
            dusk="run-import-action-button"
            @click.prevent="openConfirmationModal(action)"
            class="btn btn-default btn-detached-action"
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
import _ from 'lodash'
import { Errors, InteractsWithResourceInformation } from 'laravel-nova'

export default {
    mixins: [InteractsWithResourceInformation],

    props: {
        selectedResources: {
            type: [Array, String],
            default: () => 'all',
        },
        resourceName: String,
        endpoint: {
            type: String,
            default: null,
        },
        queryString: {
            type: Object,
            default: () => ({
                currentSearch: '',
                encodedFilters: '',
                currentTrashed: '',
                viaResource: '',
                viaResourceId: '',
                viaRelationship: '',
            }),
        },
    },

    data: () => ({
        working: false,
        errors: new Errors(),
        selectedActionKey: '',
        confirmActionModalOpened: false,
        actions: [],
    }),

    watch: {
        /**
         * Watch the actions property for changes.
         */
        actions() {
            this.selectedActionKey = ''
            this.initializeActionFields()
        },

    },

    /**
     * Mount the component and retrieve its initial data.
     */
    async created() {
        this.getActions()
    },

    methods: {
        /**
         * Get the actions available for the current resource.
         */
        getActions() {
            this.actions = []
            this.pivotActions = null
            return Nova.request()
                .get(`/nova-api/${this.resourceName}/actions`, {
                    params: {
                        viaResource: this.viaResource,
                        viaResourceId: this.viaResourceId,
                        viaRelationship: this.viaRelationship,
                        relationshipType: this.relationshipType,
                    },
                })
                .then(response => {
                    this.actions = _.filter(response.data.actions, action => {
                        return action.showOnIndexToolbar
                    })
                })
        },

        /**
         * Confirm with the user that they actually want to run the selected action.
         */
        openConfirmationModal(action) {
            this.selectedActionKey = action.uriKey
            this.confirmActionModalOpened = true
        },

        /**
         * Close the action confirmation modal.
         */
        closeConfirmationModal() {
            this.selectedActionKey = ''
            this.confirmActionModalOpened = false
        },

        /**
         * Initialize all of the action fields to empty strings.
         */
        initializeActionFields() {
            _(this.allActions).each(action => {
                _(action.fields).each(field => {
                    field.fill = () => ''
                })
            })
        },

        /**
         * Execute the selected action.
         */
        executeAction() {
            this.working = true

            Nova.request({
                method: 'post',
                url: this.endpoint || `/nova-api/${this.resourceName}/action`,
                params: this.actionRequestQueryString,
                data: this.actionFormData(),
            })
                .then(response => {
                    this.confirmActionModalOpened = false
                    this.handleActionResponse(response.data)
                    this.working = false
                })
                .catch(error => {
                    this.working = false

                    if (error.response.status == 422) {
                        this.errors = new Errors(error.response.data.errors)
                    }
                })
        },

        /**
         * Gather the action FormData for the given action.
         */
        actionFormData() {
            return _.tap(new FormData(), formData => {
                formData.append('resources', this.selectedResources)

                _.each(this.selectedAction.fields, field => {
                    field.fill(formData)
                })
            })
        },

        /**
         * Handle the action response. Typically either a message, download or a redirect.
         */
        handleActionResponse(response) {
            if (response.message) {
                this.$emit('actionExecuted')
                this.$toasted.show(response.message, { type: 'success' })
            } else if (response.deleted) {
                this.$emit('actionExecuted')
            } else if (response.danger) {
                this.$emit('actionExecuted')
                this.$toasted.show(response.danger, { type: 'error' })
            } else if (response.download) {
                let link = document.createElement('a')
                link.href = response.download
                link.download = response.name
                document.body.appendChild(link)
                link.click()
                document.body.removeChild(link)
            } else if (response.redirect) {
                window.location = response.redirect
            } else if (response.push) {
              this.$router.push(response.push).catch(err => { this.$router.go() })
            } else if (response.openInNewTab) {
                window.open(response.openInNewTab, '_blank')
            } else {
                this.$emit('actionExecuted')
                this.$toasted.show(this.__('The action ran successfully!'), { type: 'success' })
            }
        },
    },

    computed: {
        detachedActions() {
            return _.filter(this.allActions, a => a.detachedAction || false)
        },

        selectedAction() {
            if (this.selectedActionKey) {
                return _.find(this.allActions, a => a.uriKey == this.selectedActionKey)
            }
        },

        /**
         * Get the query string for an action request.
         */
        actionRequestQueryString() {
            return {
                action: this.selectedActionKey,
                search: this.queryString.currentSearch,
                filters: this.queryString.encodedFilters,
                trashed: this.queryString.currentTrashed,
                viaResource: this.queryString.viaResource,
                viaResourceId: this.queryString.viaResourceId,
                viaRelationship: this.queryString.viaRelationship,
            }
        },


        /**
         * Get all of the available actions.
         */
        allActions() {
            return this.actions
        },

    },

}
</script>
