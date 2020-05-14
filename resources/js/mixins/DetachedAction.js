import _ from 'lodash'
import {InteractsWithResourceInformation} from 'laravel-nova'
import HandlesActions from '@nova/mixins/HandlesActions'

export default {
  mixins: [HandlesActions, InteractsWithResourceInformation],

  data: () => ({
    actionsList: [],
    selectedResources: 'all',
    confirmActionModalOpened: false,
  }),

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
      this.actionsList = []
      return Nova.request()
        .get(`/nova-api/${this.resourceName}/actions`, {
          params: {
            viaResource: this.viaResource,
            viaResourceId: this.viaResourceId,
            viaRelationship: this.viaRelationship,
            relationshipType: this.relationshipType
          }
        })
        .then(response => {
          this.handleResponse(response)
        })
    },

    /**
     * Confirm with the user that they actually want to run the selected action.
     */
    openConfirmationModal(action) {
      this.selectedActionKey = action.uriKey
      this.confirmActionModalOpened = true
    }

  },

  computed: {
    /**
     * Get all of the detached actions.
     */
    detachedActions() {
      return _.filter(this.allActions, a => a.detachedAction || false)
    },

    /**
     * Get all of the available actions.
     */
    allActions() {
      return this.actionsList
    }
  }
}
