import _ from 'lodash'
import InteractsWithResourceInformation from '@/mixins/InteractsWithResourceInformation'
import HandlesActions from '@/mixins/HandlesActions'

export default {
  mixins: [HandlesActions, InteractsWithResourceInformation],

  data: () => ({
    visibleActionsDefaultLimit: 3,
    actionsList: [],
    confirmActionModalOpened: false,
    invisibleActionsOpen: false,
  }),

  created() {
    Nova.$on('actionExecuted', () => {
      Nova.$emit('refresh-resources')
    })
  },

  methods: {
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
     * Get all the detached actions.
     */
    detachedActions() {
      return _.filter(this.actionsList, action => action.detachedAction || false)
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
        : this.visibleActionsDefaultLimit;
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
