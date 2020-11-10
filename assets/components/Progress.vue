<template>
    <div class="progress rounded-pill bg-transparent" style="height: 45px;">
        <div :class="[cssBackgroundColor, 'progress-bar progress-bar-striped progress-bar-animated']"
             role="progressbar"
             :style="progressStyle"
             :aria-valuenow="progress"
             aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</template>

<script>
export default {
    props: {
        progress: {
            type: Number,
            required: true
        },
        cssBackgroundColor: {
            type: String,
            required: false
        },
    },
    computed: {
        progressStyle () {
            return 'width: ' + this.progress + '%'
        }
    }
}
</script>

