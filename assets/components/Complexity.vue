<template>
    <h5>
        <strong
            :class="['float-right badge text-light', cssBackgroundColor]"
            v-b-tooltip.hover.left
            title="Complexity">{{ complexity }}</strong>
    </h5>
</template>

<script>
export default {
    props: {
        cssBackgroundColor: {
            type: String,
            required: true
        },
        complexity: {
            type: Number,
            required: false
        }
    }
}
</script>
