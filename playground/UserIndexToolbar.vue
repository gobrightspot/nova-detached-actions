<template>
    <div class="w-full flex justify-end">
        <button class="btn btn-default btn-primary">
            Ban
        </button>
    </div>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId'],
  }
</script>