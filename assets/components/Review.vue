<template>
    <div>
        <b-avatar variant="dark" size="2rem" class="mr-2" v-b-tooltip.hover.top :title="reviewer.data.name" :src="reviewer.data.avatar_url"></b-avatar>
        <i :class="reviewIcon(mergeRequest, reviewer.data.username)"></i>
    </div>
</template>

<script>
export default {
    props: {
        mergeRequest: {
            type: Object,
            required: true
        },
        reviewer: {
            type: Object,
            required: true
        },
        fontAwesomeStyle: {
            type: String,
            required: true
        }
    },
    methods: {
        reviewIcon(mergeRequest, username) {
            var icon = 'fa-spin fa-spinner-third';
            if (this.hasUpvoted(mergeRequest, username)) {
                icon = 'fa-thumbs-up'
            }
            if (this.hasDownvoted(mergeRequest, username)) {
                icon = 'fa-thumbs-down'
            }
            return [this.fontAwesomeStyle, icon].join(' ')
        },
        hasUpvoted(mergeRequest, username) {
            let upvoted = false
            mergeRequest.upvotes.forEach((upvote) => {
                if (upvote.username === username) {
                    upvoted = true
                }
            })
            return upvoted
        },
        hasDownvoted(mergeRequest, username) {
            let downvoted = false
            mergeRequest.downvotes.forEach((downvote) => {
                if (downvote.username === username) {
                    downvoted = true
                }
            })
            return downvoted
        },
    }
}
</script>
