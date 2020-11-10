<template>
    <div id="app">
        <nav class="navbar fixed-top navbar-light bg-dark shadow">
            <a class="navbar-brand text-light" :href="dashboardUrl">
                <img :src="logoUrl" height="36" class="d-inline-block align-top mr-1" loading="lazy">
                Mezzo | Flow
            </a>
            <h5>
                <strong
                    class="float-right badge badge-secondary text-light mr-4"
                    v-b-tooltip.hover.left
                    title="Engaged points">{{ engagedPoints }}</strong>
            </h5>
        </nav>
        <div class="container-fluid">
            <MergeRequestList ref="mergeRequestList"/>
        </div>
    </div>
</template>

<script>
import MergeRequestList from './List.vue'

export default {
    components: {
        MergeRequestList
    },
    data () {
        return {
            engagedPoints: 0
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.engagedPoints = 0
            for (let i = 0; i < this.$refs.mergeRequestList.$refs.mergeRequestItem.length; i++) {
                this.engagedPoints += this.$refs.mergeRequestList.$refs.mergeRequestItem[i].mergeRequest.complexity ?? 0
            }
        })
    },
    computed: {
        logoUrl () {
            return window.AppConfig.logoUrl
        },
        dashboardUrl () {
            return window.AppConfig.dashboardUrl
        },
    },
}
</script>
