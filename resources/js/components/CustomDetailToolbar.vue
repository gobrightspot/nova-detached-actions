<template>
    <div class="w-full flex">
        <component v-if="hasComponent" :is="component" />
        <div class="flex w-full justify-end items-center">
            <detached-actions-detail :resourceName="resourceName" />
        </div>
    </div>
</template>
<script>
  export default {
    props: ['resourceName'],
    computed: {
      component() {
        return `${this.resourceName}-detail-toolbar`;
      },
      hasComponent() {
        return this.component in this.$options.components;
      }
    }
  }
</script>