<template>
    <div id="app">
        <b-navbar type="dark" class="shadow" fixed="top" variant="dark">
            <a class="navbar-brand text-light" :href="dashboardUrl">
                <img :src="logoUrl" height="36" class="d-inline-block align-top mr-1" loading="lazy">
                Mezzo | Flow
            </a>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-text
                        v-b-tooltip.hover.bottom
                        title="Current sprint">
                        {{ currentSprint }}
                    </b-nav-text>
                    <b-nav-item v-b-modal.modal-monitor href="#"
                                v-b-tooltip.hover.bottom
                                title="Burndown chart">
                        <i :class="[fa, 'fa-monitor-heart-rate']"></i>
                    </b-nav-item>
                </b-navbar-nav>
            </b-collapse>

            <b-navbar-nav class="col-6 ml-auto">
                <b-nav-text style="width:100%">
                    <b-progress
                        show-progress
                        v-b-tooltip.hover.bottom
                        :title="engagedPoints + ' point(s) distribution'"
                        max="100"
                        class="mr-2"
                        height="2rem">
                        <b-progress-bar
                            v-for="progress in distribution"
                            :value="progress.points"
                            :class="'bg-' + progress.username"
                            :max="engagedPoints"
                        >
                            {{ progress.username }} ({{ progress.points }})
                        </b-progress-bar>
                    </b-progress>
                </b-nav-text>
            </b-navbar-nav>
        </b-navbar>
        <div class="container-fluid">
            <MergeRequestList ref="mergeRequestList"/>
        </div>
        <b-modal id="modal-monitor"
                 title="Sprint overview"
                 content-class="shadow"
                 centered
                 @hide="hide"
                 hide-header
                 hide-footer
                 size="xl"
                 body-bg-variant="dark"
                 body-text-variant="light"
        >
            <i :class="[fa, 'fa-chart-line-down']"></i>
            <b-nav-text>Burndown</b-nav-text>
            <div class="container-fluid text-center">
                <b-spinner v-show="!iframe.loaded"></b-spinner>
                <iframe class="border-0 shadow"
                        width="100%"
                        height="600"
                        id="burndown"
                        @load="load"
                        v-show="iframe.loaded"
                        :src="iframe.src">
                </iframe>
            </div>
        </b-modal>
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
            iframe: {
                src: window.AppConfig.burndown.url,
                loaded: false
            },
            engagedPoints: 0,
            currentSprint: window.AppConfig.burndown.currentSprint,
            distribution: []
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.developers.forEach((developer) => {
                this.distribution.push({
                    username: developer.data.username,
                    points: 0,
                })
            })
            this.engagedPoints = 0
            for (let i = 0; i < this.$refs.mergeRequestList.$refs.mergeRequestItem.length; i++) {
                let complexity = this.$refs.mergeRequestList.$refs.mergeRequestItem[i].mergeRequest.complexity
                let dev = this.$refs.mergeRequestList.$refs.mergeRequestItem[i].mergeRequest.author.username
                this.engagedPoints += complexity
                // if developer is part of the team
                // and
                // if developer is the author of the merge-request
                this.distribution.forEach((dist, index) => {
                    if (dist.username === dev) {
                        this.distribution[index].points += complexity
                    }
                })
            }
        })
    },
    methods: {
        hide() {
            this.iframe.loaded = false
        },
        load() {
            this.iframe.loaded = true
        }
    },
    computed: {
        developers () {
            return window.AppConfig.developers
        },
        logoUrl () {
            return window.AppConfig.logoUrl
        },
        dashboardUrl () {
            return window.AppConfig.dashboardUrl
        },
        fa () {
            return window.AppConfig.fontAwesomeStyle
        },
    },
}
</script>
