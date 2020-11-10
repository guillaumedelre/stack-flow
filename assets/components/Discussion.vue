<template>
    <div>
        <i :class="[fontAwesomeStyle, 'fa-comments mr-2']"
           v-b-tooltip.hover.top
           title="Unresolved discussion(s)"></i>
        {{ unresolvedBlockingDiscussions }}
    </div>
</template>

<script>
export default {
    props: {
        unresolvedBlockingDiscussions: {
            type: Number,
            required: true
        },
        fontAwesomeStyle: {
            type: String,
            required: true
        }
    }
}
</script>
