<template>
    <div>
        <i :class="[fontAwesomeStyle, icon, 'mr-2']" v-b-tooltip.hover.top title="Last build status"></i>
        {{ status }}
    </div>
</template>

<script>
export default {
    props: {
        status: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            required: true
        },
        fontAwesomeStyle: {
            type: String,
            required: true
        }
    }
}
</script>
