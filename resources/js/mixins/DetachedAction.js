import _ from 'lodash'
import InteractsWithResourceInformation from '@/mixins/InteractsWithResourceInformation'
import HandlesActions from '@/mixins/HandlesActions'

export default {
  mixins: [HandlesActions, InteractsWithResourceInformation],

  data: () => ({
    visibleActionsDefault: 3,
    actionsList: [],
    selectedResources: 'all',
    confirmActionModalOpened: false,
    invisibleActionsOpen: false,
  }),

  /**
   * Mount the component and retrieve its initial data.
   */
  async created() {
    this.getDetachedActions()

    Nova.$on('actionExecuted', () => {
      Nova.$emit('refresh-resources')
    })
  },

  methods: {
    /**
     * Get the actions available for the current resource.
     */
    getDetachedActions() {
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
     * Determine whether the action should redirect or open a confirmation modal
     */
    determineActionStrategy(action) {

      this.selectedActionKey = action.uriKey;

      if (this.selectedAction.withoutConfirmation) {
        this.executeAction()
      } else {
        this.openConfirmationModal()
      }
    },

    /**
     * Handle a click on an action.
     *
     * @param {Object} action
     */
    handleClick(action) {
      return this.determineActionStrategy(action);
    },
  },

  computed: {
    /**
     * Get all of the detached actions.
     */
    detachedActions() {
      return _.filter(this.allActions, a => a.detachedAction || false)
    },

    /**
     * Get the visible detached actions.
     */
    visibleActions() {
      return this.visibleActionsLimit == 0
        ? []
        : this.detachedActions.slice(0, this.visibleActionsLimit)
    },

    /**
     * Get the invisible detached actions.
     */
    invisibleActions() {
      return this.detachedActions.slice(this.visibleActionsLimit)
    },

    /**
     * Get the visible actions limit.
     */
    visibleActionsLimit() {
      return this.resourceInformation.hasOwnProperty('visibleActionsLimit')
        ? this.resourceInformation.visibleActionsLimit
        : this.visibleActionsDefault;
    },

    /**
     * Get all of the available actions.
     */
    allActions() {
      return this.actionsList
    },

    /**
     * Show the arrow to the right of the dropdown trigger.
     */
    showInvisibleActionsArrow() {
      return this.resourceInformation.hasOwnProperty('showInvisibleActionsArrow')
        ? this.resourceInformation.showInvisibleActionsArrow
        : false
    },

    /**
     * Specify the icon to use on the dropdown trigger.
     */
    invisibleActionsIcon() {
      return this.resourceInformation.hasOwnProperty('invisibleActionsIcon')
          ? this.resourceInformation.invisibleActionsIcon
          : 'hero-more-horiz'
    },

    /**
     * Determine whether to show the dropdown trigger.
     */
    shouldShowInvisibleActions() {
      return this.detachedActions.length > this.visibleActionsLimit
    }
  }
}
