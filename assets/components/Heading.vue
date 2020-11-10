<template>
    <div class="col text-muted lead">
        <b-avatar variant="dark" v-b-tooltip.hover.top :title="author.name" :src="author.avatar_url"></b-avatar>
        <span class="align-middle mx-2">{{ redmineId ? '#' + redmineId : sourceBranch}}</span>
        <span v-if="doNotMergeBitch"
              v-b-tooltip.hover.right
              class="badge badge-pill badge-dark"
              title="DO NOT MERGE BITCH"><i
            :class="[fontAwesomeStyle, 'fa-lock']"></i></span>
    </div>

</template>

<script>
export default {
    props: {
        author: {
            type: Object,
            required: true
        },
        doNotMergeBitch: {
            type: Boolean,
            required: true
        },
        fontAwesomeStyle: {
            type: String,
            required: true
        },
        sourceBranch: {
            type: String,
            required: true
        },
        redmineId: {
            type: Number,
            required: false
        }
    }
}
</script>
